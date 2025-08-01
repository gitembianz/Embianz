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
        Schema::table('carts', function (Blueprint $table) {

             if (!Schema::hasColumn('carts', 'delivery_price_vat')) {

                Schema::table('carts', function (Blueprint $table) {
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
        //
    }
};
