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
        Schema::table('telegram_chat_member_logs', function (Blueprint $table) {
            $table->longtext('text')->change();
        });

        Schema::table('telegram_chat_member_error_logs', function (Blueprint $table) {
            $table->longtext('text')->change();
        });

        Schema::table('telegram_unban_schedule_error_logs', function (Blueprint $table) {
            $table->longtext('text')->change();
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
