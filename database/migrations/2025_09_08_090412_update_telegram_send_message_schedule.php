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
        Schema::table('telegram_send_message_schedules', function (Blueprint $table) {
            $table->dropColumn('message_photo');
            $table->dropColumn('message_text');
            $table->dropColumn('message_keyboard');
            $table->dropColumn('message_entities');
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
