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
        Schema::table('mini_app_banners', function (Blueprint $table) {
            $table->unsignedBigInteger('mini_app_page_with_video_id')->after('button_text_color')->nullable();
            $table->boolean('mini_app_page_with_video_show_banner')->after('mini_app_page_with_video_id')->default(0);
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
