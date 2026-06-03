<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\MiniAppBlockType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MiniAppBlockType::create(['name' => 'Баннер']);
        MiniAppBlockType::create(['name' => 'Текст']);
        MiniAppBlockType::create(['name' => 'Видео']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
