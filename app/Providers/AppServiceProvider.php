<?php

namespace App\Providers;

use App\Services\User\AuthService;
use App\Services\User\PasswordService;
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
        // $this->app->singleton(PasswordService::class, function ($app)
        // {
        //     return new PasswordService();
        // });
        // $this->app->singleton(AuthService::class, function ($app)
        // {
        //     return new AuthService(new PasswordService());
        // });
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