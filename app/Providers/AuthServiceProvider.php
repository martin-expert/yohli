<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('viewWebSocketsDashboard', function ($user = null) {
            if(env('APP_ENV') == 'local'){
                return true;
            }
            return in_array($user->email, [
                'emmanuel@jumeni.com', 'emmarthurson@gmail.com','emradegh@gmail.com'
            ]);
        });

        //
    }
}
