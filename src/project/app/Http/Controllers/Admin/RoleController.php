<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Role;
use App\Utility\Table;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Repositories\RoleRepository;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Routing\ResponseFactory;

class RoleController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table();
    }

    /**
     *
     * @return View
     */
    public function access()
    {
        $this->pageInfo->title = 'مدریت سطوح دسترسی';

        return view('admin.pages.roles.access', RoleRepository::access(),);
    }

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'مدریت دسته بندی کاربران';

        $params = [
            'url' => route('admin.roles.ajax'),
            'model' => Str::replace('_', '.', (new Role())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.roles.index', $params);
    }

    /**
     *
     * get roles
     *
     * @param Request $request
     */
    public function roles(Request $request)
    {
        $this->pageInfo->title = 'مدریت دسته بندی کاربران';

        try {
            $roles = $this->allRoles();

            $models = $roles;

            if (! auth()->user()->isAdministrator()) {
                $roles = $roles->filter(function ($role) {
                    return $role->name !== 'superadmin';
                });
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.roles.data', compact('roles'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'table' => $this->table->toArray(),
                    'models' => $models,
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allRoles($fields = '*')
    {
        return $this->table->get(Role::query(), $fields);
    }

    /**
     *
     * show view edit role
     *
     * @param Role $role
     * @return View|RedirectResponse
     */
    public function edit(Role $role): View|RedirectResponse
    {
        if ($role->name === 'superadmin') {
            return to_route('admin.roles.index');
        }

        $this->pageInfo->title = 'ویرایش اجازه دسترسی';

        return view('admin.pages.roles.create', compact('role'));
    }

    /**
     *
     * update role
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        try {
            if (Str::contains($request->input('name'), ' ')) {
                return back()->withInput()->withError('نام دسترسی شامل حرف اسپیس نمی تواند باشد');
            }

            RoleRepository::update($role, $request->validated());

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

        return redirectAfterSave($request->get('save_type'), $role);
    }

    /**
     *
     * show create view role
     *
     * @return View
     */
    public function create() : View
    {
        $this->pageInfo->title = 'ایجاد اجازه دسترسی';

        return view('admin.pages.roles.create');
    }

    /**
     *
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        try {
            if (Str::contains($request->input('name'), ' ')) {
                return back()->withInput()->withError('نام دسترسی شامل حرف اسپیس نمی تواند باشد');
            }

            $role = RoleRepository::create($request->validated());

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

        return redirectAfterSave($request->get('save_type'), $role);
    }

    /**
     *
     * destroy role
     *
     * @param Role $role
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();

            RoleRepository::delete($role);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'حذف با موفقیت انجام شد',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     *
     * delete multi selected role
     *
     * @param RoleRequest $request
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(RoleRequest $request)
    {
        try {
            DB::beginTransaction();

            RoleRepository::multipleDestroy($request->input('ids'));

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'حذف با موفقیت انجام شد',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Add Permissions To Role
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addPermissionsToRole(Request $request): RedirectResponse
    {
        try {
            RoleRepository::addPermissionsToRole($request->input('role'));

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

        return to_route('admin.roles.access');
    }


    public function export()
    {
        $cols = __('columns.roles');

        $roles = $this->allRoles(array_keys($cols));

        $roles = $roles->filter(function ($role) {
            return $role->name !== 'superadmin';
        });

        $list = [];
        foreach ($roles as $role) {
            $list[] = [
                'name' => $role->name,
                'created_at' => $role->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'roles';

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
