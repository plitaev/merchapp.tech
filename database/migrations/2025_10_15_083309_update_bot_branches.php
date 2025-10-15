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
        Schema::table('bot_branches', function (Blueprint $table) {
            $table->renameColumn('access_for_new_users_decline_bot_message_id', 'new_users_bot_message_id');
            $table->renameColumn('access_for_guests_decline_bot_message_id', 'guests_bot_message_id');
            $table->renameColumn('access_for_members_decline_bot_message_id', 'members_bot_message_id');
            $table->renameColumn('access_for_banneds_decline_bot_message_id', 'banneds_bot_message_id');
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
