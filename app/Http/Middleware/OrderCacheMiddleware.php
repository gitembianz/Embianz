<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class OrderCacheMiddleware
{
  public static ?array $payments = null;
  public static ?array $activeCountries = null;

  public function handle(Request $request, Closure $next)
  {
    if (!$request->routeIs('order')) {
      return $next($request);
    }

    if (self::$payments === null) {
      self::$payments = $this->loadGlobalPayments();
    }

    view()->share('globalPayments', self::$payments);

    foreach (self::$payments as $payment) {
      app()->instance('global_' . $payment['name'], $payment);
    }

    if (self::$activeCountries === null) {
      self::$activeCountries = $this->loadActiveCountries();
    }

    return $next($request);
  }

  private function loadGlobalPayments(): array
  {
    if (!Schema::hasTable('payments')) {
      return [];
    }

    return Cache::rememberForever('global_payments', function () {
      return Payment::query()
        ->select('id', 'active', 'type', 'name')
        ->get()
        ->keyBy('id')
        ->toArray();
    });
  }

  private function loadActiveCountries(): array
  {
    try {
      return Cache::rememberForever('active_countries', function () {

        $countries = DB::table('countries')
          ->where('status', true)
          ->select('id', 'name', 'iso_code')
          ->orderBy('name')
          ->get()
          ->keyBy('id')
          ->toArray();

        $counties = DB::table('counties')
          ->where('status', true)
          ->select('id', 'country_id', 'name', 'iso_code')
          ->orderBy('name')
          ->get()
          ->groupBy('country_id');

        $cities = DB::table('cities')
          ->where('status', true)
          ->select('id', 'county_id', 'name')
          ->orderBy('name')
          ->get()
          ->groupBy('county_id');


        $countriesSimple = collect($countries)
          ->map(fn($c) => [
            'id'       => $c->id,
            'name'     => $c->name,
            'iso_code' => $c->iso_code,
          ])
          ->values()
          ->toArray();


        $folder = base_path('public/js/countries');
        if (file_exists($folder)) {
          $this->deleteDirectory($folder);
        }
        mkdir($folder, 0777, true);

        foreach ($countries as $country) {

          $countryCounties = $counties[$country->id] ?? collect();

          if ($countryCounties->isEmpty()) continue;

          $countryData = [
            'id'       => $country->id,
            'name'     => $country->name,
            'iso_code' => $country->iso_code,
            'counties' => $countryCounties->map(function ($county) use ($cities) {

              return [
                'id'         => $county->id,
                'country_id' => $county->country_id,
                'name'       => $county->name,
                'iso_code'   => $county->iso_code,
                'cities'     => ($cities[$county->id] ?? collect())
                  ->map(fn($city) => [
                    'id'        => $city->id,
                    'county_id' => $city->county_id,
                    'name'      => $city->name,
                  ])
                  ->values()
                  ->toArray(),
              ];
            })->values()->toArray(),
          ];

          $fileName = str_replace(' ', '_', $country->name) . '.json';
          $filePath = "$folder/$fileName";

          file_put_contents($filePath, json_encode($countryData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
          chmod($filePath, 0777);
        }

        $version = now()->format('YmdHi');
        Cache::forever('countries_version', $version);
        app()->instance('countries_version', $version);

        return $countriesSimple;
      });
    } catch (\Exception $e) {
      return [];
    }
  }


  private function deleteDirectory($dir)
  {
    if (!is_dir($dir)) return;

    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object !== '.' && $object !== '..') {
        $path = $dir . DIRECTORY_SEPARATOR . $object;
        if (is_dir($path)) {
          $this->deleteDirectory($path);
        } else {
          unlink($path);
        }
      }
    }
    rmdir($dir);
  }
}
