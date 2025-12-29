<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->decimal('price', 18, 2)->nullable()->change();
            $table->decimal('base_price', 18, 2)->nullable()->change();
            $table->decimal('current_bid', 18, 2)->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('bids', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->change();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->decimal('ad_daily_price', 18, 2)->default(10000)->change();
            $table->decimal('auction_daily_price', 18, 2)->default(5000)->change();
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->change();
            $table->decimal('base_price', 10, 2)->nullable()->change();
            $table->decimal('current_bid', 10, 2)->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        Schema::table('bids', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->decimal('ad_daily_price', 10, 2)->default(10000)->change();
            $table->decimal('auction_daily_price', 10, 2)->default(5000)->change();
        });
    }
};










