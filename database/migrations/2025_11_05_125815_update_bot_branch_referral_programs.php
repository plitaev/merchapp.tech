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
        Schema::table('bot_branch_referral_programs', function (Blueprint $table) {
            $table->boolean('referral_got_product_special')->after('referral_bot_user_id')->default(0);
            $table->boolean('referral_got_product_full')->after('referral_got_product_special')->default(0);
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
