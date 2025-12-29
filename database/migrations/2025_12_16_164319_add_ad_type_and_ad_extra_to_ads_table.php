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
        Schema::table('ads', function (Blueprint $table) {
            $table->string('ad_type')->nullable()->after('type');
            $table->json('ad_extra')->nullable()->after('ad_type');
        });

        // Set default 'telegram' for existing ads
        \DB::table('ads')->whereNull('ad_type')->update(['ad_type' => 'telegram']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['ad_type', 'ad_extra']);
        });
    }
};
