<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\Static_Page;

class StaticPageRouteServiceProvider extends ServiceProvider
{
  public function boot()
  {
    try {
      $pages = Cache::get('static_pages');

      if (!$pages) {
        return;
      }

      Route::group([
        'middleware' => ['web', 'site.off']
      ], function () use ($pages) {

        foreach ($pages as $page) {
          Route::get($page->route, function () use ($page) {
            return view('store.page', ['page' => $page]);
          })->name($page->route);
        }
      });
    } catch (\Exception $e) {
      return;
    }
  }
}
