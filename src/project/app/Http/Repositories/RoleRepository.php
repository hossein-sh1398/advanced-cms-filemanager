<?php

namespace App\Http\Repositories;

use Exception;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;

class RoleRepository
{
    /**
     *
     * @return array
     */
    public static function access(): array
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();

        $permissions = Permission::all();

        $rolesPermissions = array();

        foreach ($roles as $role) {
            $can = false;

            foreach ($permissions as $permission) {
                $can = $role->hasPermission($permission);

                $rolesPermissions[$role->name][$permission->name] = $can;
            }
        }

        $permissions = $permissions->groupBy('group');

        return [
            'permissions' => $permissions, 'roles' => $roles, 'rolesPermissions' => $rolesPermissions
        ];
    }

    /**
     * Undocumented function
     *
     * @param  $roleRequest
     * @return void
     */
    public static function addPermissionsToRole($roleRequest)
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();

        foreach ($roles as $role) {

            if (isset($roleRequest[$role->name])) {
                $permissions = Permission::whereIn('name', $roleRequest[$role->name])->pluck('id')->toArray();
            } else {
                $permissions = [];
            }

            $list = [
                'admin.roles.add.permissions.to.role', 'admin.permissions.sync', 'admin.roles.index', 'admin.roles.ajax', 'admin.roles.access'
            ];

            $bool = auth()->user()->roles->contains($role->id);

            if ($bool) {
                foreach ($list as $value) {
                    $permission = Permission::where('name', $value)->firstOrFail();
                    if ($role->permissions->contains($permission->id)) {
                        if ($permissions) {
                            if (! in_array($permission->id, $permissions)) {
                                $permissions[] = $permission->id;
                            }
                        } else {
                            $permissions[] = $permission->id;
                        }
                    }
                }
            }

            $role->permissions()->sync($permissions);
        }
    }

    /**
     * update role
     *
     * @param array $name
     * @return bool
     */
    public static function update(Role $role, $data): bool
    {
        $data['name'] = Str::lower($data['name']);

        return $role->update($data);
    }

    /**
     * create role
     *
     * @param array $data
     * @return Role
     */
    public static function create($data): Role
    {
        $data['name'] = Str::lower($data['name']);

        return Role::create($data);
    }

    /**
     * delete role
     *
     * @param Role $role
     * @return mixed
     */
    public static function delete(Role $role)
    {
        if ($role->users->count()) {
            throw new Exception('به علت ثبت شدن کاربر برای این رول، حذف رول مورد نظر امکان پذیر نمی باشد');
        }

        if ($role->name === 'superadmin') {
            throw new Exception('خطا در حذف ');
        }

        if (auth()->user()->roles->contains($role->id)) {
            throw new Exception('به علت وابستگی به رول مورد نظر امکان حذف رول وجود ندارد');
        }

        if ($role->permissions->count()) {
            $role->permissions()->sync([]);
        }

        $role->delete();

        return true;
    }

    /**
     * delete multi selected role
     *
     * @param array $ids
     * @return bool
     */
    public static function multipleDestroy(array $ids): bool
    {
        $roles = Role::whereIn('id', $ids)->get();

        foreach($roles as $role) {
            self::delete($role);
        }

        return true;
    }
}
