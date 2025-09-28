<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\ProdamusPaymentMethod;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ProdamusPaymentMethod::create(['code' => '1', 'name' => 'полная предварительная оплата до момента передачи предмета расчёта']);
        ProdamusPaymentMethod::create(['code' => '2', 'name' => 'частичная предварительная оплата до момента передачи предмета расчёта']);
        ProdamusPaymentMethod::create(['code' => '3', 'name' => 'аванс']);
        ProdamusPaymentMethod::create(['code' => '4', 'name' => 'полная оплата в момент передачи предмета расчёта']);
        ProdamusPaymentMethod::create(['code' => '5', 'name' => 'частичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит']);
        ProdamusPaymentMethod::create(['code' => '6', 'name' => 'передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит']);
        ProdamusPaymentMethod::create(['code' => '7', 'name' => 'оплата предмета расчёта после его передачи с оплатой в кредит']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
