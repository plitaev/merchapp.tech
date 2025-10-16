<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotMessageButton;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotMessageButton::create(['name' => 'Тариф']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
