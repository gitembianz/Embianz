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
    Schema::table('all_jobs', function (Blueprint $table) {
      //
      if (!Schema::hasColumn('all_jobs', 'active')) {

        Schema::table('all_jobs', function (Blueprint $table) {
          $table->boolean('active')->nullable()->default(false);
        });
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
  }
};
