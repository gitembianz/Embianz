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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'invoice_date')) {
                $table->date('invoice_date')->nullable();
            }

            if (!Schema::hasColumn('orders', 'invoice_series')) {
                $table->string('invoice_series')->nullable();
            }

            if (!Schema::hasColumn('orders', 'external_invoice_number')) {
                $table->string('external_invoice_number')->nullable();
            }

            if (!Schema::hasColumn('orders', 'storno_date')) {
                $table->date('storno_date')->nullable();
            }

            if (!Schema::hasColumn('orders', 'external_storno_number')) {
                $table->string('external_storno_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'invoice_date')) {
                $table->dropColumn('invoice_date');
            }

            if (Schema::hasColumn('orders', 'invoice_series')) {
                $table->dropColumn('invoice_series');
            }

            if (Schema::hasColumn('orders', 'external_invoice_number')) {
                $table->dropColumn('external_invoice_number');
            }

            if (Schema::hasColumn('orders', 'storno_date')) {
                $table->dropColumn('storno_date');
            }

            if (Schema::hasColumn('orders', 'external_storno_number')) {
                $table->dropColumn('external_storno_number');
            }
        });
    }
};