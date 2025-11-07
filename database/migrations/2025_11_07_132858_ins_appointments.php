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
        BotMessageAppointment::where('alias', 'SYS_RP_REFERRER_NOTICE_WHEN_ONE_REFERAL_JOIN')->update(['name' => 'Реферрер - уведомление, когда присоединился 1 реферал', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_REFERAL_JOIN_1']);

        BotMessageAppointment::create(['name' => 'Реферрер - уведомление, когда присоединился 2 реферал', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_REFERAL_JOIN_2']);
        BotMessageAppointment::create(['name' => 'Реферрер - уведомление, когда присоединился 3 реферал', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_REFERAL_JOIN_3']);
        BotMessageAppointment::create(['name' => 'Реферрер - уведомление, когда присоединился 4 реферал', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_REFERAL_JOIN_4']);
        BotMessageAppointment::create(['name' => 'Реферрер - уведомление, когда присоединился 5 реферал', 'alias' => 'SYS_RP_REFERRER_NOTICE_WHEN_REFERAL_JOIN_5']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
