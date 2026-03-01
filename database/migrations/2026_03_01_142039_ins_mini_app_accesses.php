<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\MiniAppPageAccess;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MiniAppPageAccess::create(['name' => 'По дате окончания подписки']);
        MiniAppPageAccess::create(['name' => 'По периоду подписки']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
