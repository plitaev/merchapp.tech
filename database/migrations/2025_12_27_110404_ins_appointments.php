<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\Core\BotMessageAppointment::create(['name' => 'Рекуррент в боте провален второй раз', 'alias' => 'BOT_PAYMENT_RECURRENT_FAIL_SECOND']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
