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
    if (!Schema::hasTable('article_categories')) {
      Schema::create('article_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->boolean('active')->default(true);
        $table->longText('short_description')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->string('seo_title')->nullable();
        $table->string('seo_id')->unique()->nullable();
        $table->string('created_by')->nullable();
        $table->string('last_modified_by')->nullable();
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('article_categories', 'name')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->string('name')->nullable()->after('id');
        });
      }
      if (!Schema::hasColumn('article_categories', 'active')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->string('name')->nullable()->after('name');
        });
      }
      if (!Schema::hasColumn('article_categories', 'short_description')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->longText('short_description')->nullable()->after('active');
        });
      }
      if (!Schema::hasColumn('article_categories', 'start_date')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->date('start_date')->nullable()->after('long_description');
        });
      }
      if (!Schema::hasColumn('article_categories', 'end_date')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->date('end_date')->nullable()->after('start_date');
        });
      }
      if (!Schema::hasColumn('article_categories', 'seo_title')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->string('seo_title')->nullable()->after('end_date');
        });
      }
      if (!Schema::hasColumn('article_categories', 'seo_id')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->string('seo_id')->nullable()->after('seo_title');
        });
      }
      if (!Schema::hasColumn('article_categories', 'created_by')) {
        Schema::table('article_categories', function (Blueprint $table) {
          $table->string('created_by')->nullable()->after('seo_id')->default('administrator');
        });
      }
      if (!Schema::hasColumn('article_categories', 'last_modified_by')) {
        Schema::table('article_categories', function (Blueprint $table) {
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
    Schema::dropIfExists('article_categories');
  }
};
