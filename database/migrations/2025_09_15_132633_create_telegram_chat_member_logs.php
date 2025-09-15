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
        Schema::create('telegram_chat_member_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_user_id');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('chat_id');
            $table->string('status', 50);
            $table->json('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_chat_member_logs');
    }
};
