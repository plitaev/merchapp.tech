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
        FunnelCondition::create(['name' => 'Пользователь с рекуррентом забанен', 'alias' => 'user_with_recurrent_ban']);
        FunnelCondition::create(['name' => 'Пользователь без рекуррента забанен', 'alias' => 'user_without_recurrent_ban']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
