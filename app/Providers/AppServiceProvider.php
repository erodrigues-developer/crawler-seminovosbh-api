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
        $this->app->bind(
            'App\Repositories\Crawler\ICrawlerRepo',
            'App\Repositories\Crawler\CrawlerRepo'
        );
        $this->app->bind(
            'App\Repositories\Automovel\IAutomovelRepo',
            'App\Repositories\Automovel\AutomovelRepo'
        );
    }
}
