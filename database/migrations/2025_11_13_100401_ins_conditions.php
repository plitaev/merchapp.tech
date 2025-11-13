<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\FunnelCondition;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        FunnelCondition::create(['name' => 'Пользователь подписался', 'alias' => 'user_subscribe']);
        FunnelCondition::create(['name' => 'Пользователь купил продукт', 'alias' => 'user_buy_product']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
