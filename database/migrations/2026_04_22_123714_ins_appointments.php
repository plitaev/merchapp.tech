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
        BotMessageAppointment::create(['name' => 'Бот не создан в Max', 'alias' => 'BOT_NOT_IN_MAX']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ///
    }
};
