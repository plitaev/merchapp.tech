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
            $table->unsignedInteger('yookassa_tax_system_code_id')->after('yookassa_currency')->nullable();
            $table->unsignedInteger('yookassa_vat_code_id')->after('yookassa_tax_system_code_id')->nullable();
            $table->unsignedInteger('yookassa_payment_mode_id')->after('yookassa_vat_code_id')->nullable();
            $table->unsignedInteger('yookassa_payment_subject_id')->after('yookassa_payment_mode_id')->nullable();

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
