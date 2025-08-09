<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageButtonCallback;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageButtonCallback::create(['name' => 'Отключить рекуррент пользователя', 'system_name' => 'BOT_USER_RECURRENT_DISABLE']);
        BotMessageButtonCallback::create(['name' => 'Включить рекуррент пользователя', 'system_name' => 'BOT_USER_RECURRENT_ENABLE']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
