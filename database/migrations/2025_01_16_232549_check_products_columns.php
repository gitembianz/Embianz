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
        if (!Schema::hasColumn('products', 'innerid')) {

            Schema::table('products', function (Blueprint $table) {
                $table->integer('innerid')->nullable()->after('id')->nullable();
            });
        }
        if (!Schema::hasColumn('products', 'name')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('name')->nullable()->after('innerid');
            });
        }
        if (!Schema::hasColumn('products', 'brand_id')) {

            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('brand_id')->index()->nullable()->after('name');
                $table->foreign('brand_id')->references('id')->on('brands');
            });
        }
        if (!Schema::hasColumn('products', 'brand')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('brand')->nullable()->after('brand_id');
            });
        }
        if (!Schema::hasColumn('products', 'type')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('type')->nullable()->after('brand_id')->default('standard');
            });
        }
        if (!Schema::hasColumn('products', 'parent_id')) {

            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->index()->nullable()->after('type');
                $table->foreign('parent_id')->references('id')->on('products');
            });
        }
        if (!Schema::hasColumn('products', 'sku')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('sku')->nullable()->unique()->after('parent_id');
            });
        }
        if (!Schema::hasColumn('products', 'ean')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('ean')->nullable()->unique()->after('sku');
            });
        }
        if (!Schema::hasColumn('products', 'active')) {

            Schema::table('products', function (Blueprint $table) {
                $table->boolean('active')->nullable()->after('ean');
            });
        }
        if (!Schema::hasColumn('products', 'preorder')) {

            Schema::table('products', function (Blueprint $table) {
                $table->boolean('preorder')->nullable()->default(true)->after('active');
            });
        }
        if (!Schema::hasColumn('products', 'is_new')) {

            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_new')->nullable()->after('active')->default(false);
            });
        }
        if (!Schema::hasColumn('products', 'low_stock')) {

            Schema::table('products', function (Blueprint $table) {
                $table->boolean('low_stock')->nullable()->after('active')->default(false);
            });
        }
        if (!Schema::hasColumn('products', 'comments')) {

            Schema::table('products', function (Blueprint $table) {
                $table->longText('comments')->nullable()->after('low_stock');
            });
        }
        if (!Schema::hasColumn('products', 'long_description')) {

            Schema::table('products', function (Blueprint $table) {
                $table->longText('long_description')->nullable()->after('comments');
            });
        }
        if (!Schema::hasColumn('products', 'short_description')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('short_description')->nullable()->after('long_description');
            });
        }
        if (!Schema::hasColumn('products', 'meta_description')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('meta_description')->nullable()->after('short_description');
            });
        }
        if (!Schema::hasColumn('products', 'popularity')) {

            Schema::table('products', function (Blueprint $table) {
                $table->integer('popularity')->nullable()->after('meta_description');
            });
        }
        if (!Schema::hasColumn('products', 'quantity')) {

            Schema::table('products', function (Blueprint $table) {
                $table->integer('quantity')->nullable()->after('popularity');
            });
        }
        if (!Schema::hasColumn('products', 'quantity_supplier_ordered')) {

            Schema::table('products', function (Blueprint $table) {
                $table->integer('quantity_supplier_ordered')->nullable()->after('quantity')->default(0);
            });
        }
        if (!Schema::hasColumn('products', 'start_date')) {

            Schema::table('products', function (Blueprint $table) {
                $table->date('start_date')->nullable()->after('quantity_supplier_ordered');
            });
        }
        if (!Schema::hasColumn('products', 'end_date')) {

            Schema::table('products', function (Blueprint $table) {
                $table->date('end_date')->nullable()->after('start_date');
            });
        }
        if (!Schema::hasColumn('products', 'seo_title')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('seo_title')->nullable()->after('end_date');
            });
        }
        if (!Schema::hasColumn('products', 'seo_id')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('seo_id')->nullable()->after('seo_title');
            });
        }
        if (!Schema::hasColumn('products', 'created_by')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('seo_id')->default('administrator');
            });
        }
        if (!Schema::hasColumn('products', 'last_modified_by')) {

            Schema::table('products', function (Blueprint $table) {
                $table->string('last_modified_by')->nullable()->after('created_by')->default('administrator');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};