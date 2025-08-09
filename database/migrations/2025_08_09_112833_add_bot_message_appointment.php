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
        BotMessageAppointment::create(['name' => 'Кабинет - проверка рекуррента', 'alias' => 'SYS_CABINET_CHECK_RECURRENT']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
