<?php

namespace App\Providers;

use App\Models\Payment;
use App\Models\Status;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class GlobalVariablesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadGlobalVariables();
        $this->loadGlobalStatuses();
        $this->loadGlobalPayments();
    }
    private function loadGlobalVariables()
    {
        $globalVariables = Cache::get('global_variables', function () {
            $storeSettings = Store_Settings::all()->pluck('value', 'parameter')->toArray();
            return $storeSettings;
        });

        foreach ($globalVariables as $key => $value) {
            $this->app->instance('global_' . $key, $value);
        }
    }
    private function loadGlobalPayments()
    {
        $globalPayments = Cache::get('global_payments', function () {
            $payments = Payment::all(['id', 'active', 'name'])->keyBy('id')->toArray();
            return $payments;
        });

        foreach ($globalPayments as $payment) {
            $this->app->instance('global_' . $payment['name'], $payment);
        }
    }
    private function loadGlobalStatuses()
    {
        $globalStatuses = Cache::get('global_statuses', function () {
            $statuses = Status::whereIn('type', ['cart', 'order', 'voucher'])->get();
            $statusesByType = $statuses->groupBy('type');

            $globalStatuses = [];

            foreach ($statusesByType as $type => $typeStatuses) {
                foreach ($typeStatuses as $status) {
                    $globalStatuses[$type . '_' . $status->name] = $status->id;
                }
            }

            return $globalStatuses;
        });

        foreach ($globalStatuses as $key => $value) {
            $this->app->instance('global_' . $key, $value);
        }
    }
}
