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
        if (!Schema::hasColumn('categories', 'name')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('categories', 'accepted_items')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('accepted_items')->nullable()->default(
                    'default'
                )->after('name');
            });
        }
        if (!Schema::hasColumn('categories', 'active')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('active')->nullable()->after('accepted_items');
            });
        }
        if (!Schema::hasColumn('categories', 'has_parent')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('has_parent')->nullable()->after('active');
            });
        }
        if (!Schema::hasColumn('categories', 'preload_image')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('preload_image')->nullable()->after('has_parent');
            });
        }
        if (!Schema::hasColumn('categories', 'display_variant_price')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('display_variant_price')->nullable()->after('preload_image');
            });
        }
        if (!Schema::hasColumn('categories', 'one_product_page_category')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('one_product_page_category')->nullable()->after('preload_image');
            });
        }
        if (!Schema::hasColumn('categories', 'store_tab')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('store_tab')->nullable()->after('display_variant_price');
            });
        }
        if (!Schema::hasColumn('categories', 'long_description')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->longText('long_description')->nullable()->after('display_variant_price')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'long_description_bottom')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->longText('long_description_bottom')->nullable()->after('long_description')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'short_description')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('short_description')->nullable()->after('long_description_bottom')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'meta_description')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('meta_description')->nullable()->after('short_description')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'sequence')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->integer('sequence')->nullable()->after('meta_description')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'slider_sequence')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->integer('slider_sequence')->nullable()->after('sequence')->nullable()->default(0);
            });
        }
        if (!Schema::hasColumn('categories', 'start_date')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->date('start_date')->nullable()->after('slider_sequence')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'end_date')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->date('end_date')->nullable()->after('start_date')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'seo_title')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('seo_title')->nullable()->after('end_date')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'seo_id')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('seo_id')->nullable()->after('seo_title')->nullable();
            });
        }
        if (!Schema::hasColumn('categories', 'createdby')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('createdby')->nullable()->after('seo_title')->nullable()->default('administrator');
            });
        }
        if (!Schema::hasColumn('categories', 'lastmodifiedby')) {

            Schema::table('categories', function (Blueprint $table) {
                $table->string('lastmodifiedby')->nullable()->after('createdby')->nullable()->default('administrator');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
