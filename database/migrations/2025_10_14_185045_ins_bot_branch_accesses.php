<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotBranchAccess;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotBranchAccess::create(['id' => 0, 'name' => 'Предоставить доступ']);
        BotBranchAccess::create(['id' => 1, 'name' => 'Отказать']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
