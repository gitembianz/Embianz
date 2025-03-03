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
        if (!Schema::hasColumn('order__suppliers', 'name')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'supplier_name')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('supplier_name')->nullable()->after('name');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'status')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('status')->nullable()->after('name');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'date')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->date('date')->nullable()->after('status');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'currency')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('currency')->nullable()->after('date');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'quote_currency')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('quote_currency')->nullable()->after('currency');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'exchange_id')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->unsignedBigInteger('exchange_id')->index()->nullable()->after('currency');
                $table->foreign('exchange_id')->references('id')->on('exchanges');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'sum_amount')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->decimal('sum_amount', 10, 2)->default(0)->after('currency');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'vat_sum_amount')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->decimal('vat_sum_amount', 10, 2)->default(0)->after('currency');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'final_amount')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->decimal('final_amount', 10, 2)->default(0)->after('currency');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'final_amount_quote_currency')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->decimal('final_amount_quote_currency', 10, 4)->default(0)->after('final_amount');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'created_by')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('created_by ')->nullable()->after('status');
            });
        }
        if (!Schema::hasColumn('order__suppliers', 'last_modified_by')) {

            Schema::table('order__suppliers', function (Blueprint $table) {
                $table->string('last_modified_by ')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order__suppliers', function (Blueprint $table) {
            //
        });
    }
};
