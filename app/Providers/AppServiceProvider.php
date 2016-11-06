<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\CitcuitController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $citcuit = new CitcuitController();

            $view->with('global_setting', [
              'auto_refresh' => $citcuit->parseSetting('auto_refresh'),
            ]);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
