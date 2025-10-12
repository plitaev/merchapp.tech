<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\Core\BotBranch::create(['name' => 'Главная ветка', 'alias' => 'BRANCH_MAIN']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
