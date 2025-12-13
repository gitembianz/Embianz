<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AllJob;
use App\Jobs\IPGeolocation;

class RunScheduledJobs extends Command
{
    protected $signature = 'jobs:run-scheduled';
    protected $description = 'Run dynamically scheduled jobs from AllJob table';

    public function handle()
    {
        $job = AllJob::create([
            'name' => 'IP Geolocation Job',  // ✅ Add this line
            'type' => 'session_geolocation',
            'status' => 'running',
            'started_at' => now(config('app.timezone')),
        ]);

        IPGeolocation::dispatch($job->id);

        $this->info("IP Geolocation job dispatched with AllJob ID: {$job->id}");
        
        return 0;
    }
}
