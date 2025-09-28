<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\ProdamusPaymentObject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ProdamusPaymentObject::where('name','товар')->update(['name' => 'Товар']);
        ProdamusPaymentObject::where('name','подакцизный товар')->update(['name' => 'Подакцизный товар']);
        ProdamusPaymentObject::where('name','работа')->update(['name' => 'Работа']);
        ProdamusPaymentObject::where('name','услуга')->update(['name' => 'Услуга']);
        ProdamusPaymentObject::where('name','ставка азартной игры')->update(['name' => 'Ставка азартной игры']);
        ProdamusPaymentObject::where('name','выигрыш азартной игры;')->update(['name' => 'Выигрыш азартной игры']);
        ProdamusPaymentObject::where('name','лотерейный билет')->update(['name' => 'Лотерейный билет']);
        ProdamusPaymentObject::where('name','выигрыш лотереи')->update(['name' => 'Выигрыш лотереи']);
        ProdamusPaymentObject::where('name','предоставление РИД')->update(['name' => 'Предоставление РИД']);
        ProdamusPaymentObject::where('name','платёж')->update(['name' => 'Платёж']);
        ProdamusPaymentObject::where('name','агентское вознаграждение')->update(['name' => 'Агентское вознаграждение']);
        ProdamusPaymentObject::where('name','составной предмет расчёта')->update(['name' => 'Составной предмет расчёта']);
        ProdamusPaymentObject::where('name','иной предмет расчёта')->update(['name' => 'Иной предмет расчёта']);



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
