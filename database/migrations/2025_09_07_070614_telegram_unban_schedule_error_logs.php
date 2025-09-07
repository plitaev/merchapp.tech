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
        Schema::create('telegram_unban_schedule_error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_user_id');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('chat_id');
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
