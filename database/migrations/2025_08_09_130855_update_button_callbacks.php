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

        BotMessageButtonCallback::where('system_name', 'BOT_USER_RECURRENT_DISABLE')->update(['system_name' => 'BotUserRecurrentDisable']);
        BotMessageButtonCallback::where('system_name', 'BOT_USER_RECURRENT_ENABLE')->update(['system_name' => 'BotUserRecurrentEnable']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
