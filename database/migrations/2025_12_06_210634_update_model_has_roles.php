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
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropPrimary(['model_id','model_type','role_id']);
            $table->unique(['model_id','model_type','role_id']);
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
