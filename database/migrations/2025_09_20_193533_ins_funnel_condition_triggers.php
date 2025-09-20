<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\FunnelConditionTrigger;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        FunnelConditionTrigger::create(['name' => 'В момент события', 'alias' => 'now']);
        FunnelConditionTrigger::create(['name' => 'До события', 'alias' => 'before']);
        FunnelConditionTrigger::create(['name' => 'После события', 'alias' => 'after']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
