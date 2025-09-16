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
        BotMessageAppointment::where('alias', 'RECURRENT_FAIL')->update(['alias' => 'NOTIFICATION_RECURRENT_FAIL']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
