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
        Schema::table('static__pages', function (Blueprint $table) {
            $table->boolean('display_in_footer')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('static__pages', function (Blueprint $table) {
            $table->boolean('display_in_footer')->nullable(false)->change();
        });
    }
};
