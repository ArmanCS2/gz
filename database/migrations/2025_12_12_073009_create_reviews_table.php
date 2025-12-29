<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ad_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->comment('امتیاز از 1 تا 5');
            $table->text('comment')->nullable()->comment('نظر کاربر');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            // جلوگیری از ثبت نظر تکراری توسط یک کاربر برای یک آگهی
            $table->unique(['user_id', 'ad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
