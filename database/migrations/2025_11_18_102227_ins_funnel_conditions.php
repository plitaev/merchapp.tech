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
        FunnelCondition::create(['name' => 'Реферрер без рефералов', 'alias' => 'referrer_with_no_referrals']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
