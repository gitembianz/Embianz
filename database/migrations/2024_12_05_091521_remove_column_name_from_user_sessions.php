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
        Schema::table('user_sessions', function (Blueprint $table) {
            $table->dropColumn('promotion_cookieid');
            $table->dropColumn('promotion_start_date');
            $table->dropColumn('promotion_expiration_date');
            $table->dropColumn('promotion_cooldown_timer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_sessions', function (Blueprint $table) {
            //
        });
    }
};