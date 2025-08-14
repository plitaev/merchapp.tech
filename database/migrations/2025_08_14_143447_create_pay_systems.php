<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\PaySystem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pay_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->timestamps();
        });

        PaySystem::create(['name' => 'Юкасса', 'alias' => 'yookassa']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_systems');
    }
};
