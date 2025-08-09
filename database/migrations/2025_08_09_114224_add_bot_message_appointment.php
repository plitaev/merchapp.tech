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
        BotMessageAppointment::where('alias', 'SYS_CABINET_CHECK_RECURRENT')->update(['name' => 'Кабинет - рекуррент включен', 'alias' => 'SYS_CABINET_RECURRENT_IS_ENABLED']);
        BotMessageAppointment::create(['name' => 'Кабинет - рекуррент выключен', 'alias' => 'SYS_CABINET_RECURRENT_IS_DISABLED']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
