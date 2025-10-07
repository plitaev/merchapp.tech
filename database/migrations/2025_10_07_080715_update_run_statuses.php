<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('run_statuses');

        Schema::create('run_statuses', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('name', 50);
            $table->timestamps();

            $table->unique('id');

            RunStatus::create(['id' => 0, 'name' => 'Не отправлено']);
            RunStatus::create(['id' => 1, 'name' => 'Отправлено']);
            RunStatus::create(['id' => 2, 'name' => 'Зарезервировано']);
            RunStatus::create(['id' => 3, 'name' => 'Отменено']);
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
