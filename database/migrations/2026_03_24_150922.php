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
            $table->unsignedBigInteger('video_max_id')->after('video')->nullable();
            $table->unsignedBigInteger('audio_max_id')->after('audio')->nullable();
            $table->unsignedBigInteger('custom_file_max_id')->after('custom_file')->nullable();
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
