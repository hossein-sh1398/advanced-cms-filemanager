<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Utility\Table;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Utility\Export\Export;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Requests\Admin\PermissionRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    public function __construct($enableAuthorize = true)
    {
        parent::__construct($enableAuthorize);

        $this->table = new Table(['isPaginate' => true, 'isCsv' => true,]);
    }
    /**
     *
     * @return Red
     */
    public function sync(): RedirectResponse
    {
        try {
            DB::transaction(function () {
                PermissionRepository::sync();
            });
        } catch (\Exception $error) {
            return redirect()
                ->route('admin.roles.access')
                ->with('error', 'خطایی پیش اومده، لطفا مجدد تلاش نمایید!');
        }

        return redirect()
            ->route("admin.roles.access")
            ->with('success', 'عملیات با موفقیت انجام شد');
    }


    /**
     *
     * @return View
     */
    public function index(): View
    {
        $this->pageInfo->title = 'مدیریت دسترسی ها';

        $params = [
            'url' => route('admin.permissions.ajax'),
            'model' => Str::replace('_', '.', (new Permission())->getTable()),
            'table' => $this->table->toArray(),
        ];

        return view('admin.pages.permissions.index', $params);
    }

    /**
     *
     * get permissions
     *
     * @param Request $request
     */
    public function permissions(Request $request)
    {
        try {
            $permissions = $this->allPermissions();
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html'=> view('admin.pages.permissions.data', compact('permissions'))->render(),
                'links' => view('admin.theme.elements.paginate-link', [
                    'models' => $permissions,
                    'table' => $this->table->toArray(),
                    ])->render(),
            ], Response::HTTP_OK);
        }
    }

    public function allPermissions($fields = '*')
    {
        return $this->table->get(Permission::query(), $fields);
    }

    /**
     *
     * show view edit permission
     *
     * @param Permission $permission
     * @return View
     */
    public function edit(Permission $permission): View
    {
        if ($permission->global) {
            session()->flash('success', 'امکان ویرایش پرمیشن های گلوبال نیست');

            return back();
        }

        $this->pageInfo->title = 'ویرایش دسترسی';

        return view('admin.pages.permissions.create', compact('permission'));
    }

    /**
     *
     * update Permission
     *
     * @param PermissionRequest $request
     * @param Permission $Permission
     * @return RedirectResponse
     */
    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            PermissionRepository::update($permission, $request->validated());

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

        return redirectAfterSave($request->get('save_type'), $permission);
    }

    /**
     *
     * show create view Permission
     *
     * @return View
     */
    public function create() : View
    {
        $this->pageInfo->title = 'ایجاد دسترسی';

        return view('admin.pages.permissions.create');
    }

    /**
     *
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(PermissionRequest $request): RedirectResponse
    {
        try {
            $permission = PermissionRepository::create($request->validated());

            session()->flash('success', 'عملیات با موفقیت انجام شد!');
        } catch (Exception $e) {
            session()->flash('error', 'خطا در انجام عملیات، لطفا مجدد تلاش نمایید');
        }

        return redirectAfterSave($request->get('save_type'), $permission);
    }

    /**
     *
     * destroy Permission
     *
     * @param Permission $permission
     * @return JsonResponse|ResponseFactory
     */
    public function destroy(Permission $permission): JsonResponse
    {
        try {
            PermissionRepository::delete($permission);

            return response()->json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * delete multi selected Permission
     *
     * @param Request $request
     * @return JsonResponse|ResponseFactory
     */
    public function multipleDestroy(Request $request): JsonResponse
    {
        $this->validate($request, $this->rules());

        try {
            PermissionRepository::multipleDestroy($request->input('ids'));
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

    private function rules()
    {
        return [
            'ids' => ['array', 'required',],
            'ids.*' => ['exists:permissions,id'],
        ];
    }

    public function export()
    {
        $cols = __('columns.permissions');

        $permissions = $this->allPermissions(array_keys($cols));

        $list = [];

        foreach ($permissions as $permission) {
            $list[] = [
                'name' => $permission->name,
                'method' => $permission->method,
                'group' => $permission->group,
                'global' => $permission->global ? 'سیستمی' : 'معمولی',
                'created_at' => $permission->created_at,
            ];
        }

        $export = new Export();

        if (request()->input('type') === 'excel' ) {
            return $export->excel($list, [$cols]);
        } elseif (request()->input('type') === 'csv') {
            return $export->csv($list, array_values($cols),);
        } else {
            $fileName = 'permissions';

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
