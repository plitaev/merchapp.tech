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
        BotMessageAppointment::create(['name' => 'Выдача доступа в Max', 'alias' => 'SYS_SUCCESS_MESSAGE_MAX']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
