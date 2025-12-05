<?php

namespace App\Providers;

use App\Models\Status;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Category;
use App\Models\PriceList;
use App\Models\Promotion;
use App\Models\TextLabel;
use App\Models\Static_Page;
use App\Models\CustomScript;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;


class GlobalVariablesServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    ///
  }
  /**
   * Bootstrap services.
   */
  public function boot()
  {
    $this->loadActivePages();
    $this->loadGlobalVariables();
    $this->loadLabelVariables();
    $this->loadGlobalStatuses();
    $this->loadGlobalCustomScripts();
    $this->loadAllPromotionsIntoCache();
    $this->loadCategoryOneProduct();
    $this->loadGlobalCurrencies();


    // $this->loadGlobalPayments();
    // $this->loadActiveCountries();


  }

  // static pages
  private function loadActivePages()
  {
    try {
      $pages = Cache::rememberForever('static_pages', function () {
        return Static_Page::where('active', true)->get();
      });

      $this->app->instance('static_pages', $pages);
    } catch (\Exception $e) {
      return;
    }
  }

  // global settings
  private function loadGlobalVariables()
  {
    try {
      $globalVariables = Cache::rememberForever('global_variables', function () {
        return Store_Settings::pluck('value', 'parameter')->toArray();
      });

      foreach ($globalVariables as $key => $value) {
        $this->app->singleton("global_$key", fn() => $value);
      }
    } catch (\Exception $e) {
      return;
    }
  }

  // label variables
  private function loadLabelVariables()
  {
    try {
      $labelVariables = Cache::rememberForever('label_variables', function () {
        return TextLabel::pluck('value', 'parameter')->toArray();
      });

      foreach ($labelVariables as $key => $value) {
        $this->app->singleton("label_$key", fn() => $value);
      }
    } catch (\Exception $e) {
      return;
    }
  }

  // statuses
  private function loadGlobalStatuses()
  {
    try {
      $globalStatuses = Cache::rememberForever('global_statuses', function () {
        return Status::whereIn('type', ['cart', 'order', 'voucher'])
          ->get()
          ->groupBy('type')
          ->flatMap(function ($typeStatuses, $type) {
            return $typeStatuses->mapWithKeys(function ($status) use ($type) {
              return [$type . '_' . $status->name => $status->id];
            });
          })
          ->toArray();
      });

      $this->app->instance('global_statuses', $globalStatuses);
    } catch (\Exception $e) {
      return;
    }
  }

  // scripts
  private function loadGlobalCustomScripts()
  {
    try {
      $globalScripts = Cache::rememberForever('global_scripts', function () {
        return CustomScript::select(['type', 'content'])
          ->where('active', true)
          ->get()
          ->groupBy('type')
          ->map(fn($items) => $items->pluck('content')->implode(PHP_EOL));
      });

      foreach ($globalScripts as $type => $content) {
        $this->app->instance("global_script_$type", $content);
      }

      $this->app->instance("global_scripts", $globalScripts);
    } catch (\Exception $e) {
      return;
    }
  }

  // promotions
  private function loadAllPromotionsIntoCache()
  {
    try {
      if (!app()->has('global_promotion_on') || app('global_promotion_on') !== 'true') {
        return;
      }

      $promotions = Cache::rememberForever('promotions', function () {
        $today = now()->toDateString();

        return Promotion::where('active', true)
          ->whereDate('start_date', '<=', $today)
          ->whereDate('end_date', '>=', $today)
          ->get()
          ->toArray();
      });

      $this->app->singleton('promotions', function () use ($promotions) {
        return $promotions;
      });
    } catch (\Exception $e) {
      return;
    }
  }

  // one product page system
  private function loadCategoryOneProduct()
  {
    try {
      if (!app()->has('global_one_product_page_system') || app('global_one_product_page_system') !== 'true') {
        return;
      }

      $category = Cache::rememberForever('category_one_product', function () {
        return Category::where('one_product_page_category', true)
          ->with([
            'product_categories.product' => function ($query) {
              $query->select('id', 'seo_id', 'innerid', 'active', 'start_date', 'end_date')
                ->where('active', true)
                ->whereDate('start_date', '<=', now(config('app.timezone')))
                ->whereDate('end_date', '>=', now(config('app.timezone')))
                ->orderBy('innerid');
            }
          ])
          ->first();
      });

      if ($category) {
        $this->app->instance('one_product_category', $category->id);

        $products = $category->product_categories
          ->filter(fn($pc) => $pc->product)
          ->sortBy(fn($pc) => $pc->product->innerid ?? PHP_INT_MAX)
          ->map(fn($pc) => [
            'id' => $pc->product->id,
            'seo_id' => $pc->product->seo_id,
          ])
          ->unique('id')
          ->values()
          ->toArray();

        $this->app->instance('one_product_ids', $products);
      } else {
        $this->app->instance('one_product_ids', []);
        $this->app->instance('one_product_category', null);
      }
    } catch (\Exception $e) {
      return;
    }
  }

  // currencies
  private function loadGlobalCurrencies()
  {
    try {
      $globalCurrencies = Cache::rememberForever('global_currencies', function () {
        return PriceList::join('currencies', 'price_lists.currency_id', '=', 'currencies.id')
          ->where('price_lists.active', true)
          ->select(
            'price_lists.name as price_list_name',
            'currencies.name as currency_name',
            'currencies.symbol as currency_symbol'
          )
          ->orderBy('price_lists.name')
          ->get()
          ->mapWithKeys(function ($row) {
            $key = strtolower($row->price_list_name);

            return [
              $key => [
                'name'   => $row->currency_name,
                'symbol' => $row->currency_symbol,
              ],
            ];
          })
          ->toArray();
      });

      foreach ($globalCurrencies as $key => $currency) {
        $this->app->instance("global_currency_{$key}_name",   $currency['name']);
        $this->app->instance("global_currency_{$key}_symbol", $currency['symbol']);
      }
    } catch (\Exception $e) {
      return;
    }
  }







  private function loadActiveCountries()
  {
    if (
      Schema::hasTable('countries') &&
      Schema::hasTable('counties') &&
      Schema::hasTable('cities')
    ) {

      $activeCountries = Cache::rememberForever('active_countries', function () {
        $countries = Country::where('status', true)
          ->select(['id', 'name', 'iso_code'])
          ->with(['counties' => function ($query) {
            $query->where('status', true)
              ->select(['id', 'country_id', 'name', 'iso_code'])
              ->orderBy('name')
              ->with(['cities' => function ($query) {
                $query->where('status', true)
                  ->select(['id', 'county_id', 'name'])
                  ->orderBy('name');
              }]);
          }])
          ->orderBy('name')
          ->get();

        $folder = 'js/countries';

        Storage::disk('public_upload')->deleteDirectory($folder);
        Storage::disk('public_upload')->makeDirectory($folder);

        $final = [];

        foreach ($countries as $country) {
          if ($country->counties->isEmpty()) {
            continue;
          }

          $countryData = [
            'id' => $country->id,
            'name' => $country->name,
            'iso_code' => $country->iso_code,
            'counties' => $country->counties->map(function ($county) {
              return [
                'id' => $county->id,
                'country_id' => $county->country_id,
                'name' => $county->name,
                'iso_code' => $county->iso_code,
                'cities' => $county->cities->map(function ($city) {
                  return [
                    'id' => $city->id,
                    'county_id' => $city->county_id,
                    'name' => $city->name,
                  ];
                })->toArray(),
              ];
            })->toArray(),
          ];

          $fileName = str_replace(' ', '_', $country->name) . '.json';
          $filePath = $folder . '/' . $fileName;

          Storage::disk('public_upload')->put(
            $filePath,
            json_encode($countryData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
          );

          $final[] = $countryData;
        }

        // ✅ Generate version only when cache is built
        $version = now(config('app.timezone'))->format('YmdHi');
        Cache::forever('countries_version', $version);

        return $final;
      });

      // ✅ Retrieve the version from cache, not regenerate it
      $version = Cache::get('countries_version', now(config('app.timezone'))->format('YmdHi'));

      // Make both instances available globally
      $this->app->instance('countries_version', $version);
      $this->app->instance('active_countries', $activeCountries);
    }
  }





  private function loadGlobalPayments()
  {
    if (Schema::hasTable('payments')) {

      $globalPayments = Cache::rememberForever('global_payments', function () {
        $payments = Payment::all(['id', 'active', 'type', 'name'])->keyBy('id')->toArray();
        return $payments;
      });

      foreach ($globalPayments as $payment) {
        $this->app->instance('global_' . $payment['name'], $payment);
      }
    }
  }





}
