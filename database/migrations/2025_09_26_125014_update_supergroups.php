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
        SupergroupDeleteParameter::create(['name' => 'Не удалять', 'alias' => 'do_not_delete']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
