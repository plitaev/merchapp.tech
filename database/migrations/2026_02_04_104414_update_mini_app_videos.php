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
        Schema::table('mini_app_videos', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
            $table->unsignedBigInteger('duration')->after('date_open')->nullable();
            $table->string('edgecenter_name', 255)->after('edgecenter_id')->nullable();
            $table->string('edgecenter_slug', 255)->after('edgecenter_name')->nullable();
            $table->string('edgecenter_status', 255)->after('edgecenter_slug')->nullable();
            $table->string('edgecenter_screenshot_url', 255)->after('edgecenter_status')->nullable();
            $table->string('edgecenter_hls_url', 255)->after('edgecenter_screenshot_url')->nullable();
            $table->unsignedBigInteger('edgecenter_views')->after('edgecenter_hls_url')->nullable();
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
