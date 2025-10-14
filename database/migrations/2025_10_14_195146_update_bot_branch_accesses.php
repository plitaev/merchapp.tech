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
        BotBranchAccess::where('id', 0)->update(['name' => 'Отказать']);
        BotBranchAccess::where('id', 1)->update(['name' => 'Предоставить доступ']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
