<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\YookassaPaymentSubject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        YookassaPaymentSubject::create(['code' => 'commodity', 'name' => 'Товар']);
        YookassaPaymentSubject::create(['code' => 'excise', 'name' => 'Подакцизный товар']);
        YookassaPaymentSubject::create(['code' => 'job', 'name' => 'Работа']);
        YookassaPaymentSubject::create(['code' => 'service', 'name' => 'Услуга']);
        YookassaPaymentSubject::create(['code' => 'payment', 'name' => 'Платеж']);
        YookassaPaymentSubject::create(['code' => 'casino', 'name' => 'Платеж казино']);
        YookassaPaymentSubject::create(['code' => 'gambling_bet', 'name' => 'Ставка в азартной игре']);
        YookassaPaymentSubject::create(['code' => 'gambling_prize', 'name' => 'Лотерейный билет']);
        YookassaPaymentSubject::create(['code' => 'lottery', 'name' => 'Лотерейный билет']);
        YookassaPaymentSubject::create(['code' => 'lottery_prize', 'name' => 'Выигрыш в лотерею']);
        YookassaPaymentSubject::create(['code' => 'intellectual_activity', 'name' => 'Результаты интеллектуальной деятельности']);
        YookassaPaymentSubject::create(['code' => 'agent_commission', 'name' => 'Агентское вознаграждение']);
        YookassaPaymentSubject::create(['code' => 'property_right', 'name' => 'Имущественное право']);
        YookassaPaymentSubject::create(['code' => 'non_operating_gain', 'name' => 'Внереализационный доход']);
        YookassaPaymentSubject::create(['code' => 'insurance_premium', 'name' => 'Страховой сбор']);
        YookassaPaymentSubject::create(['code' => 'sales_tax', 'name' => 'Торговый сбор']);
        YookassaPaymentSubject::create(['code' => 'resort_fee', 'name' => 'Курортный сбор']);
        YookassaPaymentSubject::create(['code' => 'composite', 'name' => 'Несколько вариантов']);
        YookassaPaymentSubject::create(['code' => 'another', 'name' => 'Другое']);
        YookassaPaymentSubject::create(['code' => 'marked', 'name' => 'Товар, подлежащий маркировке средством идентификации, имеющим код маркировки, за исключением подакцизного товара (в чеке — ТМ). Пример: обувь, духи, товары легкой промышленности']);
        YookassaPaymentSubject::create(['code' => 'non_marked', 'name' => 'Товар, подлежащий маркировке средством идентификации, не имеющим кода маркировки, за исключением подакцизного товара (в чеке — ТНМ). Пример: меховые изделия']);
        YookassaPaymentSubject::create(['code' => 'marked_excise', 'name' => 'Подакцизный товар, подлежащий маркировке средством идентификации, имеющим код маркировки (в чеке — АТМ). Пример: табак']);
        YookassaPaymentSubject::create(['code' => 'non_marked_excise', 'name' => 'Подакцизный товар, подлежащий маркировке средством идентификации, не имеющим кода маркировки (в чеке — АТНМ). Пример: алкогольная продукция']);
        YookassaPaymentSubject::create(['code' => 'fine', 'name' => 'Выплата']);
        YookassaPaymentSubject::create(['code' => 'tax', 'name' => 'Страховые взносы']);
        YookassaPaymentSubject::create(['code' => 'lien', 'name' => 'Залог']);
        YookassaPaymentSubject::create(['code' => 'cost', 'name' => 'Расход']);
        YookassaPaymentSubject::create(['code' => 'agent_withdrawals', 'name' => 'Выдача денежных средств']);
        YookassaPaymentSubject::create(['code' => 'pension_insurance_without_payouts', 'name' => 'Взносы на обязательное пенсионное страхование ИП']);
        YookassaPaymentSubject::create(['code' => 'pension_insurance_with_payouts', 'name' => 'Взносы на обязательное пенсионное страхование']);
        YookassaPaymentSubject::create(['code' => 'health_insurance_without_payouts', 'name' => 'Взносы на обязательное медицинское страхование ИП']);
        YookassaPaymentSubject::create(['code' => 'health_insurance_with_payouts', 'name' => 'Взносы на обязательное медицинское страхование']);
        YookassaPaymentSubject::create(['code' => 'health_insurance', 'name' => 'Взносы на обязательное социальное страхование']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
