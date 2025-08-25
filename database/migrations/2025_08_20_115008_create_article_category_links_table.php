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
    if (!Schema::hasTable('article_category_links')) {
      Schema::create('article_category_links', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('article_id')->index();
        $table->foreign('article_id')->references('id')->on('articles');
        $table->unsignedBigInteger('category_id')->index();
        $table->foreign('category_id')->references('id')->on('article_categories');
        $table->boolean('primary_category')->nullable()->default(false);
        $table->string('created_by')->nullable();
        $table->string('last_modified_by')->nullable();
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('article_category_links', 'article_id')) {
        Schema::table('article_category_links', function (Blueprint $table) {
          $table->unsignedBigInteger('article_id')->index();
          $table->foreign('article_id')->references('id')->on('articles');
        });
      }
      if (!Schema::hasColumn('article_category_links', 'category_id')) {
        Schema::table('article_category_links', function (Blueprint $table) {
          $table->unsignedBigInteger('category_id')->index();
          $table->foreign('category_id')->references('id')->on('article_categories');
        });
      }
      if (!Schema::hasColumn('article_category_links', 'primary_category')) {
        Schema::table('article_category_links', function (Blueprint $table) {
          $table->boolean('primary_category')->nullable()->default(false);
        });
      }
      if (!Schema::hasColumn('article_category_links', 'created_by')) {
        Schema::table('article_category_links', function (Blueprint $table) {
          $table->string('created_by')->nullable()->after('seo_id')->default('administrator');
        });
      }
      if (!Schema::hasColumn('article_category_links', 'last_modified_by')) {
        Schema::table('article_category_links', function (Blueprint $table) {
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
    Schema::dropIfExists('article_category_links');
  }
};
