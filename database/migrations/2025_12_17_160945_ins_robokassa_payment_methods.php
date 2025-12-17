<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\RobokassaPaymentMethod;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        RobokassaPaymentMethod::create(['code' => 'full_prepayment', 'name' => 'Предоплата 100%']);
        RobokassaPaymentMethod::create(['code' => 'prepayment', 'name' => 'Предоплата']);
        RobokassaPaymentMethod::create(['code' => 'advance', 'name' => 'Аванс']);
        RobokassaPaymentMethod::create(['code' => 'full_payment', 'name' => 'Полный расчёт']);
        RobokassaPaymentMethod::create(['code' => 'partial_payment', 'name' => 'Частичный расчёт и кредит']);
        RobokassaPaymentMethod::create(['code' => 'credit', 'name' => 'Передача в кредит']);
        RobokassaPaymentMethod::create(['code' => 'credit_payment', 'name' => 'Оплата кредита']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
