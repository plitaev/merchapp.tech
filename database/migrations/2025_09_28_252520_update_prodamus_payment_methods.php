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
        ProdamusPaymentMethod::where('name', 'полная предварительная оплата до момента передачи предмета расчёта')->update(['name' => 'Полная предварительная оплата до момента передачи предмета расчёта']);
        ProdamusPaymentMethod::where('name', 'частичная предварительная оплата до момента передачи предмета расчёта')->update(['name' => 'Частичная предварительная оплата до момента передачи предмета расчёта']);
        ProdamusPaymentMethod::where('name', 'аванс')->update(['name' => 'Аванс']);
        ProdamusPaymentMethod::where('name', 'полная оплата в момент передачи предмета расчёта')->update(['name' => 'Полная оплата в момент передачи предмета расчёта']);
        ProdamusPaymentMethod::where('name', 'частичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит')->update(['name' => 'Частичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит']);
        ProdamusPaymentMethod::where('name', 'передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит')->update(['name' => 'Передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит']);
        ProdamusPaymentMethod::where('name', 'оплата предмета расчёта после его передачи с оплатой в кредит')->update(['name' => 'Оплата предмета расчёта после его передачи с оплатой в кредит']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
