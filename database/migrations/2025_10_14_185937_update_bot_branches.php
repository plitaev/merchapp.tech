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
            $table->renameColumn('access_for_new_users', 'new_users_bot_branch_access_id');
            $table->renameColumn('access_for_guests', 'guests_bot_branch_access_id');
            $table->renameColumn('access_for_members', 'members_bot_branch_access_id');
            $table->renameColumn('access_for_banneds', 'banneds_bot_branch_access_id');
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
