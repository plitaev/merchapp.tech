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
        Listener::create(['name' => 'Ввод номера телефона', 'alias' => 'listen_phone']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
