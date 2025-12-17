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
        BotMessageAppointment::create(['name' => 'Пользователю нужно ввести кол-во покупок', 'alias' => 'SYS_USER_NEED_PAY_COUNT']);
        BotMessageAppointment::create(['name' => 'Пользователь ввёл кол-во покупок', 'alias' => 'SYS_USER_ENTERED_PAY_COUNT']);
        BotMessageAppointment::create(['name' => 'Пользователь ввёл кол-во покупок не числом', 'alias' => 'SYS_USER_ENTERED_PAY_COUNT_NON_INT']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
