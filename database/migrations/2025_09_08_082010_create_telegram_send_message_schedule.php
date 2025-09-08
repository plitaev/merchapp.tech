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
        Schema::create('telegram_send_message_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sending_id');
            $table->unsignedBigInteger('chat_id');
            $table->json('message_photo')->nullable();
            $table->text('message_text');
            $table->json('message_keyboard')->nullable();
            $table->json('message_entities')->nullable();
            $table->boolean('run_status')->default(0);
            $table->boolean('send_status')->default(0);
            $table->unsignedBigInteger('message_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_send_message_schedule');
    }
};
