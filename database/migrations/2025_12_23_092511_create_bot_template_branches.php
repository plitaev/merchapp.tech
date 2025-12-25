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
        Schema::create('bot_template_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_template_id');
            $table->unsignedBigInteger('bot_branch_type');
            $table->unsignedBigInteger('bot_branch_product_id')->nullable();
            $table->string('name',255);
            $table->string('alias',255);
            $table->char('hash',64);
            $table->string('datetime_start',255);
            $table->string('datetime_end',255);
            $table->tinyInteger('end_by_restart')->default(1);
            $table->tinyInteger('new_users_bot_branch_access_id')->default(1);
            $table->unsignedBigInteger('new_users_bot_message_id')->nullable();
            $table->tinyInteger('guests_bot_branch_access_id')->default(1);
            $table->unsignedBigInteger('guests_bot_message_id')->nullable();
            $table->tinyInteger('members_bot_branch_access_id')->default(1);
            $table->unsignedBigInteger('members_bot_message_id')->nullable();
            $table->tinyInteger('banneds_bot_branch_access_id')->default(1);
            $table->unsignedBigInteger('banneds_bot_message_id')->nullable();
            $table->integer('referal_program_max_referrals_count')->nullable();
            $table->tinyInteger('referal_program_product_id_for_referrer')->nullable();
            $table->unsignedBigInteger('bot_branch_referrer_sending_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_template_branches');
    }
};
