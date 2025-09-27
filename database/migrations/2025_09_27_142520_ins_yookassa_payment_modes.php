<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\YookassaPaymentMode;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        YookassaPaymentMode::create(['code' => 'full_prepayment', 'name' => 'Полная предоплата']);
        YookassaPaymentMode::create(['code' => 'partial_prepayment', 'name' => 'Частичная предоплата']);
        YookassaPaymentMode::create(['code' => 'advance', 'name' => 'Аванс']);
        YookassaPaymentMode::create(['code' => 'full_payment', 'name' => 'Полный расчет']);
        YookassaPaymentMode::create(['code' => 'partial_paymen', 'name' => 'Частичный расчет и кредит']);
        YookassaPaymentMode::create(['code' => 'credit', 'name' => 'Кредит']);
        YookassaPaymentMode::create(['code' => 'credit_payment', 'name' => 'Выплата по кредиту']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
