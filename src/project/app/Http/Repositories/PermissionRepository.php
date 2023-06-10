<?php

namespace App\Http\Repositories;

use Exception;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionRepository
{
    /**
     *
     * @return void
     */
    public static function sync()
    {
        $routes = Route::getRoutes()->getRoutesByName();

            $routesFilter = collect($routes)->filter(function ($value) {
                $routeName = $value->action['as'];

                return checkCustomerRouteName($routeName);
            });

            $allRoutes = $routesFilter->keys()->all();

            $permissions = Permission::select('id', 'name', 'global')->where('global', true)->get();

            $hasPermissions = $permissions->whereIn('name', $allRoutes);

            $removePermissions = $permissions->whereNotIn('name', $allRoutes);

            $makePermissions = array_diff($allRoutes, $hasPermissions->pluck('name')->toArray());

            Permission::whereIn('id', $removePermissions->pluck('id')->toArray())->delete();

            foreach ($makePermissions as $makePermission) {
                $explode = explode('.', $makePermission);

                $data = [
                    'name' => $makePermission,
                    'global' => true,
                    'group' => $explode[1] ?? $makePermission,
                    'method' => $routesFilter[$makePermission]->methods()[0],
                ];

                Permission::create($data);
            }
    }

    /**
     * get all permissions
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public static function  index(Request $request, $fields): LengthAwarePaginator
    {
        $search = $request->input('query')  ?? '';
        $sort = $request->input('sort')  ?? 'name';
        $dir = $request->input('dir')  ?? 'desc';
        $size = $request->input('size')  ?? 10;

        return Permission::select($fields)->search($search)->orderBy($sort, $dir)->paginate($size)->withQueryString();
    }

    /**
     * update permission
     *
     * @param array $array
     * @return bool
     */
    public static function update(Permission $permission, $array): bool
    {
        return $permission->update($array);
    }

    /**
     * create permission
     *
     * @param array $data
     * @return Permission
     */
    public static function create(array $data): Permission
    {
        return Permission::create($data);
    }

    /**
     * delete permission
     *
     * @param Permission $permission
     * @return bool
     */
    public static function delete(Permission $permission): bool
    {
        if ($permission->global) {
            throw new Exception('امکان حذف پرمیشن های گلوبال نیست');
        }

        return $permission->delete();
    }

    /**
     * delete multi selected permission
     *
     * @param array $ids
     * @return bool
     */
    public static function multipleDestroy(array $ids): bool
    {
        $permissions = Permission::whereIn('id', $ids)->get();

        foreach($permissions as $permission) {
            self::delete($permission);
        }

        return true;
    }
}
