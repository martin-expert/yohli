<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;
use Genealabs\NovaPassportManager\NovaPassportManager;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                'emmarthurson@gmail.com',
                'emmanuel@jumeni.com',
                'info@yohli.com'
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new \App\Nova\Metrics\NewUsers)->width('1/4'),
            (new \App\Nova\Metrics\UsersPerDay)->width('1/4'),
            // (new \App\Nova\Metrics\UsersPerPlan)->width('1/4'),
            (new \App\Nova\Metrics\NewJobs)->width('1/4'),
            (new \App\Nova\Metrics\JobsPerDay)->width('1/4'),
            (new \App\Nova\Metrics\NewSubscriptions)->width('1/4'),
            (new \App\Nova\Metrics\SubscriptionsPerDay)->width('1/4'),
            (new \App\Nova\Metrics\NewBids)->width('1/4'),
            (new \App\Nova\Metrics\BidsPerDay)->width('1/4'),
            (new \App\Nova\Metrics\NewInvites)->width('1/4'),
            (new \App\Nova\Metrics\InvitesPerDay)->width('1/4'),
            (new \App\Nova\Metrics\JobsStatus)->width('1/4'),
            (new \App\Nova\Metrics\MilestonesStatus)->width('1/4'),
            
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
            new NovaPassportManager,
            new \Dniccum\CustomEmailSender\CustomEmailSender(),
            new \Davidpiesse\NovaMaintenanceMode\Tool(),
            new \Spatie\BackupTool\BackupTool(), 
            new \Cloudstudio\ResourceGenerator\ResourceGenerator(),
            new \Itainathaniel\AdminNotes\AdminNotes(),
            new \Themsaid\CashierTool\CashierTool(),
 
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
