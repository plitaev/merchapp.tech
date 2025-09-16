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
        BotMessageAppointment::create(['name' => 'Уведомление за 3 дня до списания рекуррента', 'alias' => 'NOTIFICATION_RECURRENT_ON_3_DAYS']);
        BotMessageAppointment::create(['name' => 'Уведомление за 1 день до списания рекуррента', 'alias' => 'NOTIFICATION_RECURRENT_ON_1_DAYS']);
        BotMessageAppointment::create(['name' => 'Уведомление за 3 дня без рекуррента', 'alias' => 'NOTIFICATION_RECURENT_OFF_3_DAYS']);
        BotMessageAppointment::create(['name' => 'Уведомление за 1 дня без рекуррента', 'alias' => 'NOTIFICATION_RECURRENT_OFF_1_DAYS']);
        BotMessageAppointment::create(['name' => 'Рекуррент успешно списан', 'alias' => 'NOTIFICATION_RECURRENT_SUCCESS_PAYMENT']);
        BotMessageAppointment::create(['name' => 'Рекуррент не удалось списать', 'alias' => 'RECURRENT_FAIL']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
