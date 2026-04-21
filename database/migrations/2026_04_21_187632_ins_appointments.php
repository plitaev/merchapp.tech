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
        BotMessageAppointment::create(['name' => 'Переход по ссылке из Telegram в Max для связки аккаунтов', 'alias' => 'SYS_LINK_MAX_FROM_TELEGRAM']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
