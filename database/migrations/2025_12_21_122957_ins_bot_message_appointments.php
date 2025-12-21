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
        BotMessageAppointment::create(['name' => 'Пользователь ожидает ввод телефона', 'alias' => 'USER_PHONE_ENTER_WAITING']);
        BotMessageAppointment::create(['name' => 'Пользователь ввёл телефон', 'alias' => 'USER_PHONE_ENTER_SUCCESS']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
