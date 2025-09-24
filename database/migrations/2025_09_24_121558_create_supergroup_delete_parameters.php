<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\SupergroupDeleteParameter;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supergroup_delete_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('alias', 255);
            $table->timestamps();
        });

        SupergroupDeleteParameter::create(['name' => 'В день окончания подписки', 'alias' => 'in_date_end']);
        SupergroupDeleteParameter::create(['name' => 'До дня окончания подписки', 'alias' => 'before_date_end']);
        SupergroupDeleteParameter::create(['name' => 'После дня окончания подписки', 'alias' => 'after_date_end']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supergroup_delete_parameters');
    }
};
