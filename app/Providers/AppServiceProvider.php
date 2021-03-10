<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Repository
        $this->app->bind(
            'App\Repositories\User\UserRepositoryInterface',
            'App\Repositories\User\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Store\StoreRepositoryInterface',
            'App\Repositories\Store\StoreRepository'
        );

        $this->app->bind(
            'App\Repositories\Order\OrderRepositoryInterface',
            'App\Repositories\Order\OrderRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
