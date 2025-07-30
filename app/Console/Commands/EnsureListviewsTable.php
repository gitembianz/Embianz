<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureListviewsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ensure:listviews-table';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    if (!Schema::hasTable('listviews')) {
        Schema::create('listviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('model')->nullable();
            $table->string('logic')->nullable();
            $table->json('columns')->nullable();
            $table->json('filters')->nullable();
            $table->json('sorts')->nullable();
            $table->timestamps();
        });

        $this->info('Created listviews table.');
    } else {
        $this->info('Listviews table already exists.');
    }
}
}
