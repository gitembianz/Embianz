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
        if (!Schema::hasColumn('products_categories', 'product_id')) {

            Schema::table('products_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->index();
                $table->foreign('product_id')->references('id')->on('products');
            });
        }
        if (!Schema::hasColumn('products_categories', 'category_id')) {

            Schema::table('products_categories', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->index();
                $table->foreign('category_id')->references('id')->on('categories');
            });
        }
        if (!Schema::hasColumn('products_categories', 'primary_category')) {

            Schema::table('products_categories', function (Blueprint $table) {
                $table->boolean('primary_category')->nullable()->default(false);
            });
        }
        if (!Schema::hasColumn('products_categories', 'created_by')) {

            Schema::table('products_categories', function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('primary_category')->default('administrator');
            });
        }
        if (!Schema::hasColumn('products_categories', 'last_modified_by')) {

            Schema::table('products_categories', function (Blueprint $table) {
                $table->string('last_modified_by')->nullable()->after('created_by')->default('administrator');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_categories', function (Blueprint $table) {
            //
        });
    }
};