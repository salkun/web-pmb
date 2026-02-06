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
        require_once app_path('Helpers/AuthHelper.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (session()->has('user') && !isset($view->getData()['profile'])) {
                $user_id = session('user.id');
                $profile = \App\Models\Profile::where('user_id', $user_id)->first();
                $view->with('profile', $profile);
            }
        });
    }
}
