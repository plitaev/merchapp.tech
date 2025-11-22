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
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 6', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_6']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 7', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_7']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 8', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_8']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 9', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_9']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 10', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_10']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
