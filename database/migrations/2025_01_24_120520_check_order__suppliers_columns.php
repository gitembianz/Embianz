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
