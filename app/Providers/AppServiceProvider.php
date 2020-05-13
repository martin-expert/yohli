<?php

namespace App\Providers;


use App\Models\Bid;
use App\Observers\BidObserver;
use Illuminate\Support\ServiceProvider;
use App\Projectors\AccountBalanceProjector;
use App\Projectors\TransactionCountProjector;
use Spatie\EventSourcing\Facades\Projectionist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Projectionist::addProjectors([
            AccountBalanceProjector::class,
            TransactionCountProjector::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Bid::observe(BidObserver::class);
    }
}
