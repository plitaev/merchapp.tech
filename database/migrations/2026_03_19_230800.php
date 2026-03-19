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
        Schema::create('max_send_message_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('max_user_id');
            $table->unsignedBigInteger('bot_message_id');
            $table->text('text');
            $table->text('keyboard')->nullable();
            $table->text('max_responce_data');
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
