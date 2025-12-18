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
            $table->string('robokassa_merchant_login')->after('prodamus_npd_income_type_id')->nullable();
            $table->string('robokassa_merchant_password')->after('robokassa_merchant_login')->nullable();
            $table->string('robokassa_nomenclature_code')->after('robokassa_merchant_password')->nullable();
            $table->unsignedBigInteger('robokassa_tax_id')->after('robokassa_nomenclature_code')->nullable();
            $table->unsignedBigInteger('robokassa_payment_method_id')->after('robokassa_tax_id')->nullable();
            $table->unsignedBigInteger('robokassa_payment_object_id')->after('robokassa_payment_method_id')->nullable();
            $table->unsignedBigInteger('robokassa_vat_id')->after('robokassa_payment_object_id')->nullable();
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
