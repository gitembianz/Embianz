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
        if (!Schema::hasColumn('media', 'path')) {

            Schema::table('media', function (Blueprint $table) {
                $table->longText('path')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('media', 'sequence')) {

            Schema::table('media', function (Blueprint $table) {
                $table->integer('sequence')->nullable()->after('path');
            });
        }
        if (!Schema::hasColumn('media', 'type')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('type')->nullable()->after('sequence');
            });
        }
        if (!Schema::hasColumn('media', 'extension')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('extension')->nullable()->after('type');
            });
        }
        if (!Schema::hasColumn('media', 'name')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('name')->nullable()->after('extension');
            });
        }
        if (!Schema::hasColumn('media', 'width')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('width')->nullable()->after('name');
            });
        }
        if (!Schema::hasColumn('media', 'height')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('height')->nullable()->after('width');
            });
        }
        if (!Schema::hasColumn('media', 'size')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('size')->nullable()->after('height');
            });
        }
        if (!Schema::hasColumn('media', 'createdby')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('createdby')->nullable()->after('size');
            });
        }
        if (!Schema::hasColumn('media', 'lastmodifiedby')) {

            Schema::table('media', function (Blueprint $table) {
                $table->string('lastmodifiedby')->nullable()->after('createdby');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            //
        });
    }
};