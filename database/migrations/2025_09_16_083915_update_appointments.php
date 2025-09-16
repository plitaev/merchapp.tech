<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageAppointment;
use App\Models\Core\FunnelCondition;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageAppointment::where('name', 'Уведомление за 1 дня без рекуррента')->update(['name' => 'Уведомление за 1 день без рекуррента']);
        FunnelCondition::where('name', 'Уведомление за 1 дня без рекуррента')->update(['name' => 'Уведомление за 1 день без рекуррента']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
