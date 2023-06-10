<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\ConfigController;
use App\Models\Role;
use App\Models\User;
use App\Models\Message;
use App\Models\NewsLetter;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();
        Role::query()->truncate();
        Message::query()->truncate();
        NewsLetter ::query()->truncate();

        $this->sync();
        $this->makeRoles();
        $this->makeUsers();

        User::factory()->count(20)->create();
        Role::factory()->count(3)->create();
        Message::factory()->count(20)->create();
        NewsLetter::factory()->count(20)->create();

        $this->call(ArticleCategorySeeder::class);

        $this->call(CommentSeeder::class);

        (new ConfigController())->configGenerator();
    }

    private function makeRoles()
    {
        $roles = [['name' => 'admin', 'persian_name' => 'ادمین']];
        foreach ($roles as $role) {
            $roleStore = Role::create($role);

            $allPermissions = Permission::all()->pluck('id')->toArray();
            $roleStore->permissions()->sync($allPermissions);
        }

        Role::create([
            'name' => 'superadmin',
            'persian_name' => 'سوپر ادمین',
        ]);
    }

    private function makeUsers()
    {
        $role = Role::where('name', 'superadmin')->first();
        $users = [
           [
            'name' => 'soheil razavi',
            'email' => 'soheil.sam@gmail.com',
            'mobile'=> '09124062629',
            'password' => '123456',
            'account_verified_at' => now(),
           ],
           [
            'name' => 'hossein shirinegad',
            'email' => 'hosseinshirinegad.66@gmail.com',
            'mobile'=> '09114030262',
            'password' => 'password',
            'account_verified_at' => now(),
           ],
        ];

        foreach ($users as $user) {
           $userStored = User::create($user);
           $userStored->roles()->sync($role->id);
        }

    }

    private function sync()
    {
        $routes = Route::getRoutes()->getRoutesByName();
        $routesFilter = collect($routes)->filter(function ($value) {
            $routeName = $value->action['as'];
            return $this->checkCustomerRouteName($routeName);
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

    public function checkCustomerRouteName($routeName): bool
    {
        if (strpos($routeName, 'admin.') !== false
            || strpos($routeName, 'api.') !== false
            || strpos($routeName, 'l5-swagger.default.api') !== false
        ) {
            return true;
        }
        return false;
    }

}
