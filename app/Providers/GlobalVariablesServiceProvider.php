<?php

namespace App\Providers;

use App\Models\CustomScript;
use App\Models\Payment;
use App\Models\Status;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
        $this->loadGlobalCustomScripts();
    }
    private function loadGlobalVariables()
    {
        if (Schema::hasTable('store__settings')) {

            $globalVariables = Cache::get('global_variables', function () {
                $storeSettings = Store_Settings::all()->pluck('value', 'parameter')->toArray();
                return $storeSettings;
            });

            foreach ($globalVariables as $key => $value) {
                $this->app->instance('global_' . $key, $value);
            }
        }
    }
    private function loadGlobalCustomScripts()
    {
        if (Schema::hasTable('custom_scripts')) {

            $globalScripts = Cache::get('global_scripts', function () {
                $scripts = CustomScript::select(['id', 'name', 'type', 'content', 'active'])->where('active', true)->get()->groupBy('type');
                return $scripts->map(function ($group) {
                    return $group->pluck('content')->implode(PHP_EOL);
                });
            });

            foreach ($globalScripts as $type => $content) {
                $this->app->instance('global_script_' . $type, $content);
            }
        }
    }
    private function loadGlobalPayments()
    {
        if (Schema::hasTable('payments')) {

            $globalPayments = Cache::get('global_payments', function () {
                $payments = Payment::all(['id', 'active', 'type', 'name', 'description'])->keyBy('id')->toArray();
                return $payments;
            });

            foreach ($globalPayments as $payment) {
                $this->app->instance('global_' . $payment['name'], $payment);
            }
        }
    }
    private function loadGlobalStatuses()
    {
        if (Schema::hasTable('statuses')) {

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
}
