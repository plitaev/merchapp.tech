<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\Funnel;
use App\Models\Core\FunnelCondition;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Funnel::create(['name' => 'Уведомление за 3 дня до списания рекуррента', 'alias' => 'notification_recurrent_on_3_days']);
        Funnel::create(['name' => 'Уведомление за 1 день до списания рекуррента', 'alias' => 'notification_recurrent_on_1_days']);
        Funnel::create(['name' => 'Уведомление за 3 дня без рекуррента', 'alias' => 'notification_recurent_off_3_days']);
        Funnel::create(['name' => 'Уведомление за 1 дня без рекуррента', 'alias' => 'notification_recurrent_off_1_days']);
        Funnel::create(['name' => 'Рекуррент успешно списан', 'alias' => 'notification_recurrent_success_payment']);
        Funnel::create(['name' => 'Рекуррент не удалось списать', 'alias' => 'recurrent_fail']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
