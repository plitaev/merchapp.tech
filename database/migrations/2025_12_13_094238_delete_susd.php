<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessage;
use App\Models\Core\BotMessageAppointment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $appointment = BotMessageAppointment::where('alias', 'SYS_USER_SUBSCRIPTION_DATA')->first();
        if ($appointment) {
            BotMessage::where('bot_message_appointment_id', $appointment->id)->delete();
            BotMessageAppointment::destroy($appointment->id);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_user_branch_logs');
    }
};
