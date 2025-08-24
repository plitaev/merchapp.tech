<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\Listener;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Listener::where('alias', 'SYS_EMAIL_PAY_NOT_FOUND_FIRST')->update(['alias' => 'listen_email_pay_not_found_first']);
        Listener::create(['name' => 'Выдан доступ в канал', 'alias' => 'listen_success_message']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
