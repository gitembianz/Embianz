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
    if (!Schema::hasTable('user_sessions')) {
      Schema::create('user_sessions', function (Blueprint $table) {
        $table->id();
        $table->string('sessions')->unique();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('http_referer')->nullable();
        $table->longText('visited_url')->nullable();
        $table->string('status')->nullable();
        $table->string('country')->nullable();
        $table->string('countryCode')->nullable();
        $table->string('region')->nullable();
        $table->string('regionName')->nullable();
        $table->string('city')->nullable();
        $table->string('zip')->nullable();
        $table->string('lat')->nullable();
        $table->string('lon')->nullable();
        $table->string('timezone')->nullable();
        $table->string('isp')->nullable();
        $table->string('org')->nullable();
        $table->string('as')->nullable();
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('user_sessions', 'sessions')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('sessions')->unique()->nullable()->after('id');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'ip_address')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('ip_address', 45)->nullable()->after('sessions');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'user_agent')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->text('user_agent')->nullable()->after('ip_address');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'http_referer')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->longText('http_referer')->nullable()->after('user_agent');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'visited_url')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->longText('visited_url')->nullable()->after('http_referer');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'status')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('status')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'county')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('county')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'countyCode')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('countyCode')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'region')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('region')->nullable()->after('visited_url');
        });
      }
       if (!Schema::hasColumn('user_sessions', 'regionName')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('regionName')->nullable()->after('visited_url');
        });
      }
       if (!Schema::hasColumn('user_sessions', 'city')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('city')->nullable()->after('visited_url');
        });
      }
       if (!Schema::hasColumn('user_sessions', 'zip')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('zip')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'lat')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('lat')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'lon')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('lon')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'timezone')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('timezone')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'isp')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('isp')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'org')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('org')->nullable()->after('visited_url');
        });
      }
      if (!Schema::hasColumn('user_sessions', 'as')) {
        Schema::table('user_sessions', function (Blueprint $table) {
          $table->string('as')->nullable()->after('visited_url');
        });
      }
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
  }
};
