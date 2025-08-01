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
            if (!Schema::hasColumn('orders', 'comments')) {

                Schema::table('orders', function (Blueprint $table) {
                    $table->longText('comments')->nullable()->after('session_id');
                });
            }
            if (!Schema::hasColumn('orders', 'billing_id')) {

              Schema::table('orders', function (Blueprint $table) {
                  $table->unsignedBigInteger('billing_id')->index()->nullable();
                  $table->foreign('billing_id')->references('id')->on('addresses');
              });
            }
            if (!Schema::hasColumn('orders', 'shipping_id')) {

              Schema::table('orders', function (Blueprint $table) {
                  $table->unsignedBigInteger('shipping_id')->index()->nullable();
                  $table->foreign('shipping_id')->references('id')->on('addresses');
              });
            }
            if (!Schema::hasColumn('orders', 'avg_cost')) {

                Schema::table('orders', function (Blueprint $table) {
                    $table->decimal('avg_cost', 10, 4)->default(0)->after('promotion_value');
                });
            }
             if (!Schema::hasColumn('orders', 'delivery_price_vat')) {

                Schema::table('orders', function (Blueprint $table) {
                    $table->decimal('delivery_price_vat', 5, 2)->default(19)->after('delivery_price');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
