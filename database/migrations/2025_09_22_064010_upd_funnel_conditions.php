<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\FunnelCondition;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        FunnelCondition::where('alias', 'notification_recurrent_on_3_days')->update(['name' => '3 дня до списания рекуррента']);
        FunnelCondition::where('alias', 'notification_recurrent_on_1_days')->update(['name' => '1 день до списания рекуррента']);
        FunnelCondition::where('alias', 'notification_recurent_off_3_days')->update(['name' => '3 дня до бана без рекуррента']);
        FunnelCondition::where('alias', 'notification_recurrent_off_1_days')->update(['name' => '1 день до бана без рекуррента']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
