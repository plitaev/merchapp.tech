<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\RobokassaPaymentObject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        RobokassaPaymentObject::create(['code' => 'commodity', 'name' => 'Товар']);
        RobokassaPaymentObject::create(['code' => 'excise', 'name' => 'Подакцизный товар']);
        RobokassaPaymentObject::create(['code' => 'job', 'name' => 'Работа']);
        RobokassaPaymentObject::create(['code' => 'service', 'name' => 'Услуга']);
        RobokassaPaymentObject::create(['code' => 'gambling_bet', 'name' => 'Ставка азартной игры']);
        RobokassaPaymentObject::create(['code' => 'gambling_prize', 'name' => 'Выигрыш азартной игры']);
        RobokassaPaymentObject::create(['code' => 'lottery', 'name' => 'Лотерейный билет']);
        RobokassaPaymentObject::create(['code' => 'lottery_prize', 'name' => 'Выигрыш лотереи']);
        RobokassaPaymentObject::create(['code' => 'intellectual_activity', 'name' => 'Предоставление результатов интеллектуальной деятельности']);
        RobokassaPaymentObject::create(['code' => 'payment', 'name' => 'Платеж']);
        RobokassaPaymentObject::create(['code' => 'agent_commission', 'name' => 'Агентское вознаграждение']);
        RobokassaPaymentObject::create(['code' => 'composite', 'name' => 'Составной предмет расчета']);
        RobokassaPaymentObject::create(['code' => 'resort_fee', 'name' => 'Курортный сбор']);
        RobokassaPaymentObject::create(['code' => 'another', 'name' => 'Иной предмет расчета']);
        RobokassaPaymentObject::create(['code' => 'property_right', 'name' => 'Имущественное право']);
        RobokassaPaymentObject::create(['code' => 'non-operating_gain', 'name' => 'Внереализационный доход']);
        RobokassaPaymentObject::create(['code' => 'insurance_premium', 'name' => 'Страховые взносы']);
        RobokassaPaymentObject::create(['code' => 'sales_tax', 'name' => 'Торговый сбор']);
        RobokassaPaymentObject::create(['code' => 'tovar_mark', 'name' => 'Товар, подлежащий маркировке']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
