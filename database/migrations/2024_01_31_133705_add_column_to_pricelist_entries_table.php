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
        Schema::table('pricelist_entries', function (Blueprint $table) {
            $table->decimal('rrp_value', 10, 2)->nullable()->after('value');
            $table->integer('discount')->nullable()->after('value')->default(
                '0'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricelist_entries', function (Blueprint $table) {
            //
        });
    }
};
