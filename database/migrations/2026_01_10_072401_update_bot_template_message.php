<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotTemplateMessage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotTemplateMessage::where('id', 1)->update(['bot_message_appointment_alias' => 'SYS_WELCOME_MESSAGE']);
        BotTemplateMessage::where('id', 2)->update(['bot_message_appointment_alias' => 'SYS_REQUEST_EMAIL']);
        BotTemplateMessage::where('id', 3)->update(['bot_message_appointment_alias' => 'SYS_ENTERED_EMAIL_INCORRECT']);
        BotTemplateMessage::where('id', 4)->update(['bot_message_appointment_alias' => 'SYS_CONFIRM_EMAIL']);
        BotTemplateMessage::where('id', 5)->update(['bot_message_appointment_alias' => 'SYS_PAY_IN_BOT']);
        BotTemplateMessage::where('id', 6)->update(['bot_message_appointment_alias' => 'SYS_CABINET_RECURRENT_IS_ENABLED']);
        BotTemplateMessage::where('id', 7)->update(['bot_message_appointment_alias' => 'SYS_CABINET_RECURRENT_IS_DISABLED']);
        BotTemplateMessage::where('id', 8)->update(['bot_message_appointment_alias' => 'SYS_CABINET_AFTER_RECURRENT_DISABLED']);
        BotTemplateMessage::where('id', 9)->update(['bot_message_appointment_alias' => 'SYS_DECLINE_CHAT_JOIN_REQUEST']);
        BotTemplateMessage::where('id', 10)->update(['bot_message_appointment_alias' => 'SYS_APPROVE_CHAT_JOIN_REQUEST']);
        BotTemplateMessage::where('id', 11)->update(['bot_message_appointment_alias' => 'SYS_SUCCESS_MESSAGE']);
        BotTemplateMessage::destroy(12);
        BotTemplateMessage::where('id', 13)->update(['bot_message_appointment_alias' => 'SYS_ACCESS_EXPIRED']);
        BotTemplateMessage::where('id', 14)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_1']);
        BotTemplateMessage::where('id', 15)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_2']);
        BotTemplateMessage::where('id', 16)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_3']);
        BotTemplateMessage::where('id', 17)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_4']);
        BotTemplateMessage::where('id', 18)->update(['bot_message_appointment_alias' => 'BOT_PAYMENT_RECURRENT_FAIL']);
        BotTemplateMessage::where('id', 19)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_5']);
        BotTemplateMessage::where('id', 20)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_6']);
        BotTemplateMessage::where('id', 21)->update(['bot_message_appointment_alias' => 'FUNNEL_USER_BANNED_MESSAGE_7']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
