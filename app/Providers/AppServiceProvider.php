<?php

namespace App\Providers;

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
        //this is not nessesary, I was doing this to t shoot why i could not resolve a NotificationRequest
        $this->app->bind('NotificationRequest', function($app)
        {
            return new \App\Http\Requests\NotificaitonRequest;
        });
    }
}
