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
        BotMessageAppointment::create(['name' => 'Аккаунт Max успешно привязан по ссылке из Telegram (Сообщение для Max)', 'alias' => 'SYS_SUCCESSFUL_LINK_MAX_FROM_TELEGRAM_SEND_IN_MAX']);
        BotMessageAppointment::create(['name' => 'Аккаунт Max успешно привязан по ссылке из Telegram (Сообщение для Telegram)', 'alias' => 'SYS_SUCCESSFUL_LINK_MAX_FROM_TELEGRAM_SEND_IN_TELEGRAM']);

        BotMessageAppointment::create(['name' => 'Аккаунт Max не привязан по ссылке из Telegram (Сообщение для Max)', 'alias' => 'SYS_FAILED_LINK_MAX_FROM_TELEGRAM_SEND_IN_MAX']);
        BotMessageAppointment::create(['name' => 'Аккаунт Max не привязан по ссылке из Telegram (Сообщение для Telegram)', 'alias' => 'SYS_FAILED_LINK_MAX_FROM_TELEGRAM_SEND_IN_TELEGRAM']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ///
    }
};
