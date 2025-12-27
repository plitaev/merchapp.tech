<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\PaySystem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        PaySystem::create(['name' => 'Сгенерировано системой', 'system' => 'virtual']);
        PaySystem::create(['name' => 'Геткурс', 'system' => 'getcourse']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
