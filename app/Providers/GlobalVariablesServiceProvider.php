<?php

namespace App\Providers;

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
        $this->loadGlobalSessionId();
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

    public function loadGlobalSessionId()
    {
        // Suppressing any output here
        ob_start();

        // Load the session ID
        $sessionId = $this->getSessionId();

        // Send any buffered output before setting the cookie
        ob_end_flush();

        // Set the global session ID instance
        app()->instance('global_session_id', $sessionId);
    }

    private function getSessionId()
    {
        if (array_key_exists('sessionId', $_COOKIE)) {
            return $_COOKIE['sessionId'];
        } else {
            $sessionId = session()->getId();
            setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
            return $sessionId;
        }
    }
}
