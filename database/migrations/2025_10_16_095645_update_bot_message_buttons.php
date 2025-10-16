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
        Schema::table('bot_message_buttons', function (Blueprint $table) {
            $table->unsignedBigInteger('pay_system_id')->after('bot_message_button_type_id')->nullable();
            $table->unsignedBigInteger('product_id')->after('pay_system_id')->nullable();
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
