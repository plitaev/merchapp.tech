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
        Schema::table('bots', function (Blueprint $table) {
            $table->after('prodamus_sys', function (Blueprint $t) {
                $t->string('prodamus_url')->nullable();
            });

            $table->after('prodamus_url', function (Blueprint $t) {
                $t->string('prodamus_key')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
