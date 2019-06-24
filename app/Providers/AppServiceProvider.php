<?php

namespace App\Providers;

use App\Dao\AuthDao;
use App\Dao\AuthDaoImp;
use App\Services\AuthService;
use App\Services\AuthServiceImp;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(AuthService::class, AuthServiceImp::class);
        $this->app->bind(AuthDao::class, AuthDaoImp::class);
    }
}
