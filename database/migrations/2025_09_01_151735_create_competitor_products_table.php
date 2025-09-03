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
    if (!Schema::hasTable('competitor_products')) {
      Schema::create('competitor_products', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->longText('url')->nullable();
        $table->unsignedBigInteger('product_id')->index();
        $table->foreign('product_id')->references('id')->on('products');
        $table->decimal('internal_price', 10, 2)->nullable();
        $table->unsignedBigInteger('competitor_id')->index();
        $table->foreign('competitor_id')->references('id')->on('competitors');
        $table->string('difference_value')->nullable();
        $table->string('difference_percent')->nullable();
        $table->string('created_by')->nullable();
        $table->string('last_modified_by')->nullable();
        $table->timestamps();
      });
    }
    // If it exists but column is missing, add the column
    else {
      if (!Schema::hasColumn('competitor_products', 'name')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->string('name')->nullable()->after('id');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'price')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->decimal('price', 10, 2)->nullable()->after('name');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'url')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->string('url')->nullable()->after('price');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'product_id')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->unsignedBigInteger('product_id')->index();
        $table->foreign('product_id')->references('id')->on('products');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'internal_price')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->decimal('internal_price', 10, 2)->nullable()->after('name');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'competitor_id')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->unsignedBigInteger('competitor_id')->index();
        $table->foreign('competitor_id')->references('id')->on('competitors');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'difference_value')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->string('difference_value')->nullable()->after('name');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'created_by')) {
        Schema::table('competitor_products', function (Blueprint $table) {
          $table->string('created_by')->nullable()->after('url')->default('administrator');
        });
      }
      if (!Schema::hasColumn('competitor_products', 'last_modified_by')) {
        Schema::table('competitor_products', function (Blueprint $table) {
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
    Schema::dropIfExists('competitor_products');
  }
};
