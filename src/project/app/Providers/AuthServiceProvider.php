<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->isAdministrator()) {
                return true;
            }
        });


        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->view('email.verify-email', compact('url'));
        });

        try {
            DB::connection()->getPdo();
            if (Schema::hasTable('permissions')) {
                foreach ($this->getPermissions() as $permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        return $user->hasRole($permission->roles);
                    });
                }
            }
        } catch (\Exception $e) {

        }
    }

    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
