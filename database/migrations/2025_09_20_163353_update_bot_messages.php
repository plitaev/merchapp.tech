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
            $table->unsignedInteger('days_before_condition')->after('funnel_condition_id')->nullable();
            $table->unsignedInteger('hours_before_condition')->after('days_before_condition')->nullable();
            $table->unsignedInteger('minutes_before_condition')->after('hours_before_condition')->nullable();
            $table->unsignedInteger('days_after_condition')->after('minutes_before_condition')->nullable();
            $table->unsignedInteger('hours_after_condition')->after('days_after_condition')->nullable();
            $table->unsignedInteger('minutes_after_condition')->after('hours_after_condition')->nullable();
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
