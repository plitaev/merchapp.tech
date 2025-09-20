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
        Schema::table('bot_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('funnel_condition_trigger_id')->after('funnel_condition_id')->nullable();

            $table->renameColumn('days_before_condition', 'funnel_days');
            $table->renameColumn('hours_before_condition', 'funnel_hours');
            $table->renameColumn('minutes_before_condition', 'funnel_minutes');

            $table->dropColumn('days_after_condition');
            $table->dropColumn('hours_after_condition');
            $table->dropColumn('minutes_after_condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
