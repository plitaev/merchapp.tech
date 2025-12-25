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
        Schema::create('bot_template_message_buttons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_template_message_id');
            $table->unsignedBigInteger('bot_message_button_type_id');
            $table->unsignedBigInteger('pay_system_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name',255);
            $table->text('url')->nullable();
            $table->unsignedBigInteger('bot_message_callback_id')->nullable();
            $table->string('callback',255)->nullable();
            $table->tinyInteger('tracking')->default(1);
            $table->tinyInteger('pos')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_template_message_buttons');
    }
};
