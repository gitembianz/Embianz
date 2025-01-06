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
        if (!Schema::hasTable('order__supplier__items')) {

            Schema::create('order__supplier__items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order__supplier_id');
                $table->foreign('order__supplier_id')->references('id')->on('order__suppliers');
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products');
                $table->integer('quantity')->default(0)->nullable();
                $table->integer('quantity_received')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order__supplier__items');
    }
};
