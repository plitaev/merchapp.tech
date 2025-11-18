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
        FunnelCondition::create(['name' => 'Не купил продукт акции или РП', 'alias' => 'not_buy_branch_product']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
