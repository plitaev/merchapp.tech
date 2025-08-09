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
        Schema::create('telegram_chat_join_request_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->unsignedBigInteger('user_id');
            $table->string('invite_link');
            $table->boolean('status');
            $table->json('telegram_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_chat_join_request');
    }
};
