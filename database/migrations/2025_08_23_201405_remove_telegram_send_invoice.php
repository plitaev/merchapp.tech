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
        Schema::dropIfExists('telegram_send_invoice_logs');
        Schema::dropIfExists('telegram_send_invoice_error_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
