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
        Schema::create('bot_template_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_template_id');
            $table->unsignedBigInteger('bot_template_branch_id')->nullable();
            $table->unsignedBigInteger('bot_message_type_id');
            $table->unsignedBigInteger('bot_message_appointment_id')->nullable();
            $table->unsignedBigInteger('funnel_id')->nullable();
            $table->unsignedBigInteger('funnel_condition_id')->nullable();
            $table->unsignedBigInteger('funnel_condition_trigger_id')->nullable();
            $table->integer('funnel_days')->nullable();
            $table->unsignedBigInteger('funnel_hours')->nullable();
            $table->unsignedBigInteger('funnel_minutes')->nullable();
            $table->unsignedBigInteger('funnel_product_id')->nullable();
            $table->string('name',255);
            $table->text('text');
            $table->string('image',255)->nullable();
            $table->string('video',255)->nullable();
            $table->string('audio',255)->nullable();
            $table->string('custom_file',255)->nullable();
            $table->string('custom_file_name',255)->nullable();
            $table->tinyInteger('delete_through');
            $table->tinyInteger('delete_through_hours')->nullable();
            $table->tinyInteger('delete_through_minutes')->nullable();
            $table->tinyInteger('delete_keyboard_through')->nullable();
            $table->integer('delete_keyboard_through_days')->nullable();
            $table->integer('delete_keyboard_through_hours')->nullable();
            $table->integer('delete_keyboard_through_minutes')->nullable();
            $table->integer('pause_after_message')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_template_messages');
    }
};
