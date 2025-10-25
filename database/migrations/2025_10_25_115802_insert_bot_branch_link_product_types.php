<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\BotBranchLinkProductType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        BotBranchLinkProductType::create(['id' => 1, 'name' => 'Закрывать акцию при покупке продукта']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       //
    }
};
