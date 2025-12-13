<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AllJob;
use Illuminate\Support\Carbon;

class RunScheduledJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can call this command via: php artisan jobs:run-scheduled
     */
    protected $signature = 'jobs:run-scheduled';

    /**
     * The console command description.
     */
    protected $description = 'Run dynamically scheduled jobs from AllJob table';

    /**
     * Execute the console command.
     */
public function handle()
{
    try {
        \Log::info('Job starting', ['allJobId' => $this->allJobId]);
        
        $messages = [];
        $messages[] = "Starting IP Geolocation job with AllJob ID: {$this->allJobId}";

        $lastRun = AllJob::where('type', 'session_geolocation')
          ->where('status', 'finished')
          ->latest('finished_at')
          ->value('finished_at');

        $query = UserSessions::whereNull('status');
        if ($lastRun) {
          $query->where('created_at', '>', $lastRun);
        }

        $total = $query->count();
        $messages[] = "Found {$total} sessions to process.";
        \Log::info('Found sessions', ['total' => $total]);

        // ... rest of your code ...
        
        $this->finish('finished', $messages);
        
    } catch (\Exception $e) {
        \Log::error('Job failed', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        $this->finish('error', ['Error: ' . $e->getMessage()]);
        throw $e;
    }
}
}
