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
        BotMessageAppointment::create(['name' => 'Оплата всех тарифов полностью', 'alias' => 'SYS_PAY_IN_BOT_ALL_TARIFFS']);
        BotMessageButtonCallback::create(['name' => 'Оплата всех тарифов полностью', 'system_name' => 'GoToFullTariffs']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
