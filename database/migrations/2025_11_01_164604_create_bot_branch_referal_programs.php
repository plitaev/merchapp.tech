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
        Schema::create('bot_branch_referral_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_branch_id');
            $table->unsignedBigInteger('referral_branch_id');
            $table->unsignedBigInteger('referrer_bot_user_id')->nullable();
            $table->timestamps();

            $table->unique(['bot_branch_id', 'referral_branch_id', 'referrer_bot_user_id'], 'unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_branch_referal_programs');
    }
};
