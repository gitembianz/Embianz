<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AllJob;
use App\Jobs\IPGeolocation;
use Illuminate\Support\Carbon;

class RunScheduledJobs extends Command
{
    protected $signature = 'jobs:run-scheduled';
    protected $description = 'Run dynamically scheduled jobs from AllJob table';

    public function handle()
    {
        // Create the AllJob record
        $job = AllJob::create([
            'type' => 'session_geolocation',
            'status' => 'running',
            'started_at' => now(config('app.timezone')),
        ]);

        // Dispatch the actual job
        IPGeolocation::dispatch($job->id);

        $this->info("IP Geolocation job dispatched with AllJob ID: {$job->id}");
        
        return 0;
    }
}
