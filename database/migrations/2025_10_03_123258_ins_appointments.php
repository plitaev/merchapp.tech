<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageAppointment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageAppointment::create(['name' => 'Статистика за день', 'alias' => 'SYS_STAT_PER_DAY']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
