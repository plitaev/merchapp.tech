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
            $table->unsignedInteger('prodamus_payment_method_id')->after('yookassa_payment_subject_id')->nullable();
            $table->unsignedInteger('prodamus_payment_object_id')->after('prodamus_payment_method_id')->nullable();
            $table->unsignedInteger('prodamus_npd_income_type_id')->after('prodamus_payment_object_id')->nullable();
            $table->string('prodamus_sys', 255)->after('prodamus_npd_income_type_id')->nullable();

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
