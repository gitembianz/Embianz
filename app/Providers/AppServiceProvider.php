<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->singleton(\App\Services\CategoryService::class);
  }

  public function boot() {}
}
