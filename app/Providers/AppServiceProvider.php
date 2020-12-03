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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      view()->composer('*', function($view){
        $view_name = str_replace('.', '-', $view->getName());
        view()->share('view_name', $view_name);
        $demo_session = json_encode(\Session::get('demo_session'));
        view()->share('demo_session', $demo_session);
      });
    }
}
