<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageAppointment;
use App\Models\Core\BotMessageButtonCallback;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageAppointment::create(['name' => 'Не найден пользователь при попытке связать Max из ТГ', 'alias' => 'SYS_USER_MAX_FROM_TG_NOT_FOUND']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ///
    }
};
