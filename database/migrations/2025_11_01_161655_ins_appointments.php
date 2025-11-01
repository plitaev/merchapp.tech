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
        BotMessageAppointment::create(['name' => 'Реферрер - не участник клуба', 'alias' => 'SYS_RP_REFERRER_IS_NOT_A_MEMBER']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
