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
        Schema::create('bot_user_recurrent_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_user_id');
            $table->unsignedBigInteger('prevous_pay_id');
            $table->unsignedBigInteger('new_pay_id')->nullable();
            $table->datetime('recurrent_datetime');
            $table->boolean('run_status');
            $table->json('pay_system_responce', 255)->nullable();
            $table->timestamps();

            $table->unique(['bot_user_id', 'recurrent_datetime'], 'unique');
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
