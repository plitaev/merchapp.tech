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
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 1', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_1']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 2', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_2']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 3', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_3']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 4', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_4']);
        BotMessageAppointment::create(['name' => 'Воронка Пользователь забанен - Сообщение 5', 'alias' => 'FUNNEL_USER_BANNED_MESSAGE_5']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
