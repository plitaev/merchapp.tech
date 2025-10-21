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
        Schema::create('bot_branch_link_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_branch_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('bot_branch_link_product_type_id');
            $table->timestamps();

            $table->unique(['bot_branch_id', 'product_id', 'bot_branch_link_product_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_branch_link_products');
    }
};
