<?php

namespace App\Services;

use App\Providers\GlobalVariablesServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Psr\Log\LoggerInterface;

class GlobalCacheBuilder
{
    protected Application $app;
    protected LoggerInterface $logger;

    public function __construct(Application $app, LoggerInterface $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * Rebuild all global caches by (re)running the ServiceProvider boot.
     * This uses the provider's public boot() which calls the internal loaders.
     *
     * Returns an array with summary info for feedback.
     */
    public function rebuild(): array
    {
        $start = microtime(true);

        // Optionally clear the caches first (uncomment if desired)
        \Illuminate\Support\Facades\Cache::forget('static_pages');
        \Illuminate\Support\Facades\Cache::forget('global_variables');
        \Illuminate\Support\Facades\Cache::forget('label_variables');
        \Illuminate\Support\Facades\Cache::forget('global_statuses');
        \Illuminate\Support\Facades\Cache::forget('global_scripts');
        \Illuminate\Support\Facades\Cache::forget('promotions');
        \Illuminate\Support\Facades\Cache::forget('category_one_product');
        \Illuminate\Support\Facades\Cache::forget('global_currencies');
        \Illuminate\Support\Facades\Cache::forget('global_payments');
        \Illuminate\Support\Facades\Cache::forget('active_countries');

        try {
            // Instantiate provider and call boot()
            $provider = new GlobalVariablesServiceProvider($this->app);
            $provider->boot();

            $duration = microtime(true) - $start;
            $this->logger->info('Global cache rebuilt', ['duration' => $duration]);

            return [
                'ok' => true,
                'duration' => $duration,
                'message' => 'Global caches rebuilt successfully.',
            ];
        } catch (\Throwable $e) {
            $this->logger->error('Global cache rebuild failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'ok' => false,
                'message' => 'Global cache rebuild failed: ' . $e->getMessage(),
            ];
        }
    }
}
