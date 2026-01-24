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
        Schema::create('mini_app_video_link_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mini_app_page_id');
            $table->unsignedBigInteger('mini_app_video_id');
            $table->integer('pos');
            $table->timestamps();

            $table->unique(['mini_app_page_id', 'mini_app_video_id'], 'unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_app_video_link_pages');
    }
};
