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
            $table->dropColumn('ban_date');
            $table->dropColumn('ban_time');
            $table->datetime('ban_datetime')->after('run_status');
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
