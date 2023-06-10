<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Utility\Table;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Repositories\UserRepository;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\View\View as ViewView;

class UserController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

       $this->table = new Table();
    }

    /**
     * Undocumented function
     *
     * @return View
     */
    public function index(): ViewView
    {
        $this->pageInfo->title='لیست کاربران';

        $params = [
            'model' => Str::replace('_', '.', (new User)->getTable()),
            'url' => route('admin.users.get'),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.users.index', $params);
    }


    public function getUsers(Request $request)
    {
        try {
            $users = $this->allUsers();

            $models = $users;

            if (! auth()->user()->isAdministrator()) {
                $users = $users->filter(function ($user) {
                    return ! $user->isAdministrator();
                });
            }

            $countSuperAdmin = User::whereHas('roles', function ($query) {
                $query->where('name', 'superadmin');
            })->count();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html'=> view('admin.pages.users.data', compact('users'))->render(),
                    'links' => view('admin.theme.elements.paginate-link', [
                        'models' => $models,
                        'countSuperAdmin' => $countSuperAdmin,
                        'table' => $this->table->toArray(),
                    ])->render(),
                ], 201);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],  Response::HTTP_BAD_REQUEST);
        }

    }

    private function allUsers($fields = '*')
    {
        $array = [];

        $active = request()->input('active', 'all');

        if ($this->table->isFilter &&  $active != 'all') {
            if ($active) {
                $array[] = ['key' => 'account_verified_at', 'op' => "!=", 'value' => null];
            } else {
                $array[] = ['key' => 'account_verified_at', 'op' => "=", 'value' => null];
            }
        }

        return $this->table->get(User::filters($array), $fields);
    }

    /**
     *
     * @param User $user
     * @return void
     */
    public function edit(User $user)
    {
        $this->pageInfo->title='ویرایش کاربر';

        $user->load('roles');

        $roles = Role::when(! auth()->user()->isAdministrator(), function ($q) {
            $q->where('name', '!=', 'superadmin');
        })->pluck('name', 'id')->toArray();

        $authUser = auth()->user();

        $disabled = $authUser->isAdministrator() && $authUser->id == $user->id;

        return view('admin.pages.users.create', compact('roles', 'user', 'disabled'));
    }

    /**
     *
     * @param UserRequest $request
     * @param User $user
     * @return void
     */
    public function update(UserRequest $request, User $user)
    {
        $userData = $request->validated();

        if (is_null($request->get('password'))) {
            unset($userData['password']);
        }

        try {
            DB::beginTransaction();

            $user->update($userData);

            if (! auth()->user()->isAdministrator()) {
                $roles = Role::whereIn('id', $request->input('roles'))->pluck('name')->toArray();

                if (in_array('superadmin', $roles)) {
                    throw new Exception('خطا در ارسال اطلاعات ناردست');
                }
            }

            $user->roles()->sync($request->input('roles'));

            DB::commit();

            session()->flash('success', 'کاربر مورد نظر با موفقیت ویرایش شد');
        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', $e->getMessage());
        }

        return redirectAfterSave($request->get('save_type'), $user);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function create()
    {
        $this->pageInfo->title='ایجاد کاربر';

        $roles = Role::when(! auth()->user()->isAdministrator(), function ($query) {
            $query->where('name', '!=', 'superadmin');
        })->pluck('name', 'id')->toArray();

        return view('admin.pages.users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create($request->validated());

            $roleIds = $request->input('roles');

            if (! auth()->user()->isAdministrator()) {
                $roles = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

                if (in_array('superadmin', $roles)) {
                    throw new Exception('خطا در ارسال اطلاعات ناردست');
                }
            }

            $user->roles()->sync($roleIds);

            DB::commit();

            session()->flash('success', 'کاربر مورد نظر با موفقیت ایجاد شد');
        } catch (Exception $e) {
            DB::rollBack();

            session()->flash('error', $e->getMessage());
        }

        return redirectAfterSave($request->get('save_type'), $user);
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function destroy(User $user)
    {
        try {
            UserRepository::delete($user);

            return response()->json([
                'status' => true,
                'message' => 'حذف کاربر با موفقیت انجام شد'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function multipleDestroy(Request $request)
    {
        $this->validate($request, $this->deleteRules());

        $userIds = $request->get('ids');

        try {
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                UserRepository::delete($user);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
            'message' => 'حذف کاربر با موفقیت انجام شد'
        ]);
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function changeMultipleStatus(Request $request)
    {
        $this->validate($request, $this->deleteRules());

        try {
            $users = User::whereIn('id', $request->input('ids'))->get();

            foreach ($users as $user) {
                UserRepository::changeStatus($user);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    private function deleteRules()
    {
        return [
            'ids' => ['array', 'required'],
            'ids.*' => ['required', 'exists:users,id'],
        ];
    }

    /**
     * Undocumented function
     *
     * @param User $user
     * @return void
     */
    public function changeStatus(User $user)
    {
        try {
            UserRepository::changeStatus($user);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => true,
        ]);
    }


    public function export()
    {
        $cols = __('columns.users');

        $users = $this->allUsers(array_keys($cols));

        if (! auth()->user()->isAdministrator()) {
            $users = $users->filter(function ($user) {
                return ! $user->isAdministrator();
            });
        }

        $list = [];
        foreach ($users as $user) {
            $list[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'account_verified_at' => $user->account_verified_at ? 'بله' : 'خیر',
                'created_at' => $user->created_at,
            ];
        }

        $export = new Export();
        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, $cols);
        } else {
            $fileName = 'مدیریت کاربران';

            $pdf = PDF::loadView('admin.theme.elements.pdf', [
                'data' => $list,
                'thead' => array_values($cols),
            ], [], [
                'format' => 'A4-L'
            ]);

            return $pdf->download("$fileName.pdf");
        }
    }
}
