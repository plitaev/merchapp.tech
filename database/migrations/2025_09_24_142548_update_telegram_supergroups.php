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
        Schema::table('telegram_supergroups', function (Blueprint $table) {
            $table->unsignedBigInteger('supergroup_delete_parameter_id')->after('give_access');
            $table->integer('supergroup_delete_hours')->after('supergroup_delete_parameter_id');
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
