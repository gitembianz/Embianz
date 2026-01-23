<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'subscribe')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('subscribe')->default(false)->after('updated_at');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('orders', 'subscribe')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('subscribe');
            });
        }
    }
};
