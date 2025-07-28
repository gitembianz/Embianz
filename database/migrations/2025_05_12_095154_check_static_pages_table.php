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
       Schema::table('static__pages', function (Blueprint $table) {
            //
        if (!Schema::hasColumn('static__pages', 'active')) {

            Schema::table('static__pages', function (Blueprint $table) {
                $table->boolean('active')->nullable()->default(false);

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
