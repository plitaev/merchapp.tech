<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotBranchReferrerSending;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotBranchReferrerSending::create(['name' => 'По присоединению первого реферала', 'alias' => 'FIRST_REFERRAL']);
        BotBranchReferrerSending::create(['name' => 'По присоединению каждого реферала', 'alias' => 'EVERY_REFERRAL']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
