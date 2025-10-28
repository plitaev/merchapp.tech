<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\RunStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        RunStatus::where('id', 0)->update(['name' => 'Ожидание']);
        RunStatus::where('id', 1)->update(['name' => 'Завершено']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
