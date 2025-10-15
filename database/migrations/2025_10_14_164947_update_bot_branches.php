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

            $table->char('hash', 64)->after('alias');

            $table->boolean('access_for_new_users')->after('hash')->default(1);
            $table->unsignedBigInteger('access_for_new_users_decline_bot_message_id')->after('access_for_new_users')->nullable();

            $table->boolean('access_for_guests')->after('access_for_new_users_decline_bot_message_id')->default(1);
            $table->unsignedBigInteger('access_for_guests_decline_bot_message_id')->after('access_for_guests')->nullable();

            $table->boolean('access_for_members')->after('access_for_guests_decline_bot_message_id')->default(1);
            $table->unsignedBigInteger('access_for_members_decline_bot_message_id')->after('access_for_members')->nullable();

            $table->boolean('access_for_banneds')->after('access_for_members_decline_bot_message_id')->default(1);
            $table->unsignedBigInteger('access_for_banneds_decline_bot_message_id')->after('access_for_banneds')->nullable();

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
