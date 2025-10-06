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
        Schema::table('bot_user_ban_schedules', function (Blueprint $table) {
            $table->dropIndex('bot_user_id');
            $table->dropIndex('ban_datetime');

            $table->unique(['bot_user_id', 'ban_datetime']);
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
