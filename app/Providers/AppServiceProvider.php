<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Observers\CategoryObserver;

class AppServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->app->singleton(\App\Services\CategoryService::class);
  }

  public function boot()
  {
    Category::observe(CategoryObserver::class);
  }
}
