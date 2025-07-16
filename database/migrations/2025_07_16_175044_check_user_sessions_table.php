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
    Schema::table('user_sessions', function (Blueprint $table) {
      //
      if (!Schema::hasColumn('user_sessions', 'visited_url')) {

        Schema::table('user_sessions', function (Blueprint $table) {
          $table->longText('visited_url')->nullable()->after('http_referer');
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
