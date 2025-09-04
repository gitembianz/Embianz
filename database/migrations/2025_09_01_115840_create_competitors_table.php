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
    if (!Schema::hasTable('competitors')) {
      Schema::create('competitors', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->longText('url')->nullable();
        $table->string('created_by')->nullable();
        $table->string('last_modified_by')->nullable();
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('competitors', 'name')) {
        Schema::table('competitors', function (Blueprint $table) {
          $table->string('name')->nullable()->after('id');
        });
      }
      if (!Schema::hasColumn('competitors', 'url')) {
        Schema::table('competitors', function (Blueprint $table) {
          $table->string('url')->nullable()->after('seo_title');
        });
      }
      if (!Schema::hasColumn('competitors', 'created_by')) {
        Schema::table('competitors', function (Blueprint $table) {
          $table->string('created_by')->nullable()->after('url')->default('administrator');
        });
      }
      if (!Schema::hasColumn('competitors', 'last_modified_by')) {
        Schema::table('competitors', function (Blueprint $table) {
          $table->string('last_modified_by')->nullable()->after('created_by')->default('administrator');
        });
      }
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('competitors');
  }
};
