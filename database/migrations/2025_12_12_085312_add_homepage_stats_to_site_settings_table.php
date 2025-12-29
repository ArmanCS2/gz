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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->integer('active_ads')->default(0)->after('ad_auto_approve');
            $table->bigInteger('total_members')->default(0)->after('active_ads');
            $table->integer('active_users')->default(0)->after('total_members');
            $table->integer('successful_deals')->default(0)->after('active_users');
            $table->tinyInteger('satisfaction_percent')->default(100)->after('successful_deals');
            $table->decimal('rating', 2, 1)->default(4.9)->after('satisfaction_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'active_ads',
                'total_members',
                'active_users',
                'successful_deals',
                'satisfaction_percent',
                'rating',
            ]);
        });
    }
};
