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
        BotMessageAppointment::create(['name' => 'Акция - вход для участников клуба', 'alias' => 'SYS_BRANCH_ACCEPT_FOR_MEMBERS']);

        BotMessageAppointment::create(['name' => 'Акция - отказ для новых пользователей', 'alias' => 'SYS_BRANCH_DECLINE_FOR_NEWBIES']);
        BotMessageAppointment::create(['name' => 'Акция - прием для новых пользователей', 'alias' => 'SYS_BRANCH_ACCEPT_FOR_NEWBIES']);

        BotMessageAppointment::create(['name' => 'Акция - отказ для гостей', 'alias' => 'SYS_BRANCH_DECLINE_FOR_GUESTS']);
        BotMessageAppointment::create(['name' => 'Акция - прием для гостей', 'alias' => 'SYS_BRANCH_ACCEPT_FOR_GUESTS']);

        BotMessageAppointment::create(['name' => 'Акция - отказ для выбывших участников', 'alias' => 'SYS_BRANCH_DECLINE_FOR_BANNEDS']);
        BotMessageAppointment::create(['name' => 'Акция - прием для выбывших участников', 'alias' => 'SYS_BRANCH_ACCEPT_FOR_BANNEDS']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
