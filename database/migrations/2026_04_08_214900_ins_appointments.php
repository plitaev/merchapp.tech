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
        BotMessageAppointment::create(['name' => 'Сообщение в Max перед верификацией в Телеграм', 'alias' => 'SYS_SEND_IN_MAX_BEFORE_VERIFICATION_FROM_MAX']);
        BotMessageAppointment::create(['name' => 'Сообщение в Телеграм после верификации из Max', 'alias' => 'SYS_SEND_IN_TELEGRAM_AFTER_VERIFICATION_FROM_MAX']);
        BotMessageAppointment::create(['name' => 'Сообщение в Max после верификации в Телеграм', 'alias' => 'SYS_SEND_IN_MAX_AFTER_VERIFICATION_FROM_MAX']);

        BotMessageAppointment::create(['name' => 'Сообщение в Telegram перед верификацией в Max', 'alias' => 'SYS_SEND_IN_TELEGRAM_BEFORE_VERIFICATION_FROM_TELEGRAM']);
        BotMessageAppointment::create(['name' => 'Сообщение в Телеграм после верификации из Max', 'alias' => 'SYS_SEND_IN_MAX_AFTER_VERIFICATION_FROM_TELEGRAM']);
        BotMessageAppointment::create(['name' => 'Сообщение в Max после верификации в Телеграм', 'alias' => 'SYS_SEND_IN_TELEGRAM_AFTER_VERIFICATION_FROM_TELEGRAM']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
