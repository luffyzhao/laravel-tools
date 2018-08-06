<?php

namespace App\Providers;

use App\Repositories\Modules\Order\Provider as Order;
use App\Repositories\Modules\User\Provider as User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(Order::class);
        $this->app->register(User::class);
    }
}
