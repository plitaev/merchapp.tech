<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageAppointment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_GENERATE_LINK']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_GO_TO_HIS_LINK']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_ONE_REFERAL_JOIN']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_ALL_REFERRAL_JOIN']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_NOBODY_JOIN_1']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_NOBODY_JOIN_2']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRER_NOBODY_JOIN_3']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_IS_CLUB_MEMBER']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_JOIN_LINK_SUCCESSFUL']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_JOIN_LINK_FULL']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_JOIN_LINK_WHEN_ALREADY_JOINED']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_BUY_AFTER_JOIN']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_NOT_BUY_1']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_NOT_BUY_2']);
        BotMessageAppointment::create(['name' => '', 'alias' => 'SYS_RP_REFERRAL_NOT_BUY_3']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
