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
        Schema::create('mini_app_page_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mini_app_page_id');
            $table->unsignedBigInteger('block_type_id');
            $table->Integer('position')->nullable();
            $table->timestamps();
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
