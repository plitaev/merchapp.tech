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
        Schema::table('bot_branches', function (Blueprint $table) {
            $table->boolean('end_by_product_sale')->after('datetime_end')->default(0);
            $table->unsignedBigInteger('end_by_product_sale_product_id')->after('end_by_product_sale')->null();
            $table->boolean('end_by_restart')->after('end_by_product_sale_product_id')->default(0);
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
