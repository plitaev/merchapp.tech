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
            $table->string('tbank_terminal_key', 255)->after('prodamus_key_recurrent')->nullable();
            $table->string('tbank_terminal_password', 255)->after('tbank_terminal_key')->nullable();
            $table->string('tbank_tax_id', 255)->after('tbank_terminal_password')->nullable();
            $table->string('tbank_taxation_id', 255)->after('tbank_tax_id')->nullable();
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
