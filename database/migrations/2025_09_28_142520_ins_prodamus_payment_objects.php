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
        ProdamusPaymentObject::create(['code' => '1', 'name' => 'товар']);
        ProdamusPaymentObject::create(['code' => '2', 'name' => 'подакцизный товар']);
        ProdamusPaymentObject::create(['code' => '3', 'name' => 'работа']);
        ProdamusPaymentObject::create(['code' => '4', 'name' => 'услуга']);
        ProdamusPaymentObject::create(['code' => '5', 'name' => 'ставка азартной игры']);
        ProdamusPaymentObject::create(['code' => '6', 'name' => 'выигрыш азартной игры;']);
        ProdamusPaymentObject::create(['code' => '7', 'name' => 'лотерейный билет']);
        ProdamusPaymentObject::create(['code' => '8', 'name' => 'выигрыш лотереи']);
        ProdamusPaymentObject::create(['code' => '9', 'name' => 'предоставление РИД']);
        ProdamusPaymentObject::create(['code' => '10', 'name' => 'платёж']);
        ProdamusPaymentObject::create(['code' => '11', 'name' => 'агентское вознаграждение']);
        ProdamusPaymentObject::create(['code' => '12', 'name' => 'составной предмет расчёта']);
        ProdamusPaymentObject::create(['code' => '13', 'name' => 'иной предмет расчёта']);



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
