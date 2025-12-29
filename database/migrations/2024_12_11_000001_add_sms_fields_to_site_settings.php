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
            // اضافه کردن فیلدهای SMS
            if (!Schema::hasColumn('site_settings', 'melipayamak_username')) {
                $table->string('melipayamak_username')->nullable()->after('zarinpal_merchant_id');
            }
            if (!Schema::hasColumn('site_settings', 'melipayamak_password')) {
                $table->string('melipayamak_password')->nullable()->after('melipayamak_username');
            }
            if (!Schema::hasColumn('site_settings', 'melipayamak_from_number')) {
                $table->string('melipayamak_from_number')->nullable()->after('melipayamak_password');
            }
            
            // حذف فیلد قدیمی اگر وجود دارد
            if (Schema::hasColumn('site_settings', 'melipayamak_api_key')) {
                $table->dropColumn('melipayamak_api_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['melipayamak_username', 'melipayamak_password', 'melipayamak_from_number']);
        });
    }
};






