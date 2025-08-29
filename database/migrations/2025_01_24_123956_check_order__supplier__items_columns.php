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
        if (!Schema::hasColumn('order__supplier__items', 'order__supplier_id')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->unsignedBigInteger('order__supplier_id')->after('id');
                $table->foreign('order__supplier_id')->references('id')->on('order__suppliers');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'product_id')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->id('id');
                $table->foreign('product_id')->references('id')->on('products');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'product_quantity')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->integer('product_quantity')->nullable()->after('product_id');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'product_quantity_interim')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->integer('product_quantity_interim')->nullable()->after('product_quantity');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'quantity')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->integer('quantity')->nullable()->after('product_quantity_interim');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'quantity_received')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->integer('quantity_received')->nullable()->after('quantity');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'price')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->decimal('price', 10, 4)->nullable()->after('quantity_received');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'vat')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->decimal('vat', 10, 4)->nullable()->after('price')->default(19);
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'subtotal')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->decimal('subtotal', 10, 4)->nullable()->after('price');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'created_by')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('quantity_received');
            });
        }
        if (!Schema::hasColumn('order__supplier__items', 'last_modified_by')) {

            Schema::table('order__supplier__items', function (Blueprint $table) {
                $table->string('last_modified_by')->nullable()->after('created_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order__supplier__items', function (Blueprint $table) {
            //
        });
    }
};
