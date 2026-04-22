<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageButtonCallback;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageAppointment::create(['name' => 'Пользователь уже есть в Max', 'alias' => 'SYS_USER_ALREADY_IN_MAX']);
        BotMessageButtonCallback::create(['name' => 'Переход в Max из Telegram', 'system_name' => 'GoToMax']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ///
    }
};
