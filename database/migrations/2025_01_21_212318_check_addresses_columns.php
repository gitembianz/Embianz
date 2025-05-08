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
        Schema::table('addresses', function (Blueprint $table) {
            //
        });
        if (!Schema::hasColumn('addresses', 'account_id')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->unsignedBigInteger('account_id')->index()->nullable();
                $table->foreign('account_id')->references('id')->on('accounts');
            });
        }
        if (!Schema::hasColumn('addresses', 'first_name')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('first_name')->nullable()->after('account_id');
            });
        }
        if (!Schema::hasColumn('addresses', 'last_name')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('last_name')->nullable()->after('first_name');
            });
        }
        if (!Schema::hasColumn('addresses', 'phone')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('last_name');
            });
        }
        if (!Schema::hasColumn('addresses', 'email')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('email')->nullable()->after('phone');
            });
        }
        if (!Schema::hasColumn('addresses', 'address1')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('address1')->nullable()->after('email');
            });
        }
        if (!Schema::hasColumn('addresses', 'address2')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('address2')->nullable()->after('address1');
            });
        }
        if (!Schema::hasColumn('addresses', 'country')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('country')->nullable()->after('address2');
            });
        }
        if (!Schema::hasColumn('addresses', 'country_iso')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('country_iso')->nullable()->after('country');
            });
        }
        if (!Schema::hasColumn('addresses', 'county')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('county')->nullable()->after('country_iso');
            });
        }
        if (!Schema::hasColumn('addresses', 'county_iso')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('county_iso')->nullable()->after('county');
            });
        }
        if (!Schema::hasColumn('addresses', 'city')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('city')->nullable()->after('county_iso');
            });
        }
        if (!Schema::hasColumn('addresses', 'zipcode')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('zipcode')->nullable()->after('city');
            });
        }
        if (!Schema::hasColumn('addresses', 'type')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->string('type')->nullable()->after('zipcode');
            });
        }
        if (!Schema::hasColumn('addresses', 'is_default')) {

            Schema::table('addresses', function (Blueprint $table) {
                $table->boolean('is_default')->default(0)->after('type');
            });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
        });
    }
};
