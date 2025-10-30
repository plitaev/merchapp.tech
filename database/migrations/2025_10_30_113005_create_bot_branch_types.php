<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotBranchType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bot_branch_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('alias', 255);
            $table->timestamps();
        });

        BotBranchType::create(['name' => 'Основная ветка', 'alias' => 'BRANCH_TYPE_MAIN']);
        BotBranchType::create(['name' => 'Акция', 'alias' => 'BRANCH_TYPE_PROMOTION']);
        BotBranchType::create(['name' => 'Реферальная программа', 'alias' => 'BRANCH_TYPE_REFERAL_PROGRAM']);

        Schema::table('bot_branches', function (Blueprint $table) {
            $table->unsignedBigInteger('bot_branch_type')->after('bot_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_branch_types');
    }
};
