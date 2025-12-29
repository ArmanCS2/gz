<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('telegram_link');
            $table->integer('member_count')->default(0);
            $table->enum('type', ['normal', 'auction'])->default('normal');
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('current_bid', 10, 2)->nullable();
            $table->timestamp('auction_end_time')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->boolean('show_contact')->default(true);
            $table->enum('status', ['pending', 'active', 'rejected', 'expired', 'sold'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};











