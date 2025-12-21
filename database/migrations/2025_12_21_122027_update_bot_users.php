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
        Schema::table('bot_users', function (Blueprint $table) {
            $table->boolean('listen_phone_count_status')->after('listen_pay_count_status_timestamp')->default(0);
            $table->boolean('listen_phone_count_status_timestamp')->after('listen_phone_count_status')->nullable();
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
