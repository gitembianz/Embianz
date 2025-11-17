<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PromotionService;

class PromotionServiceProvider extends ServiceProvider
{
    public function register()
{
    $this->app->singleton('promotionService', function ($app) {
        return new \App\Services\PromotionService();
    });
}

    public function boot()
    {
        //
    }
}
