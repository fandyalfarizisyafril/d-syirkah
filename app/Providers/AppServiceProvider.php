<?php

namespace App\Providers;

use App\Services\WebsiteContent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'pages.*', 'errors.*'], function ($view) {
            $view->with('company', app(WebsiteContent::class)->get());
        });
    }
}
