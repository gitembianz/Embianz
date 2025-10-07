<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
  {
    if (!Schema::hasTable('schedule_jobs')) {
      Schema::create('schedule_jobs', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('type')->nullable();
        $table->string('status')->default('queued')->nullable();
        $table->unsignedBigInteger('job_id')->nullable();
        $table->foreign('job_id')->references('id')->on('all_jobs')->onDelete('cascade');
        $table->text('error')->nullable();
        $table->timestamp('started_at')->nullable();
        $table->timestamp('finished_at')->nullable();
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('schedule_jobs', 'name')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->string('name')->nullable();
        });
      }
      if (!Schema::hasColumn('schedule_jobs', 'type')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->string('type')->nullable();
        });
      }
      if (!Schema::hasColumn('schedule_jobs', 'status')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->string('status')->default('queued')->nullable();
        });
      }
      if (!Schema::hasColumn('schedule_jobs', 'job_id')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->unsignedBigInteger('job_id')->nullable();
          $table->foreign('job_id')->references('id')->on('all_jobs')->onDelete('cascade');
        });
      }
      if (!Schema::hasColumn('schedule_jobs', 'error')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->text('error')->nullable();
        });
      }
      if (!Schema::hasColumn('schedule_jobs', 'started_at')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->timestamp('started_at')->nullable();
        });
      }
      if (!Schema::hasColumn('schedule_jobs', 'finished_at')) {
        Schema::table('schedule_jobs', function (Blueprint $table) {
          $table->timestamp('finished_at')->nullable();
        });
      }
    }
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_jobs');
    }
};
