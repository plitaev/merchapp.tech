<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\RobokassaTax;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        RobokassaTax::create(['code' => 'osn', 'name' => 'Общая СН']);
        RobokassaTax::create(['code' => 'usn_income', 'name' => 'Упрощенная СН (доходы)']);
        RobokassaTax::create(['code' => 'usn_income_outcome', 'name' => 'Упрощенная СН (доходы минус расходы)']);
        RobokassaTax::create(['code' => 'esn', 'name' => 'Единый сельскохозяйственный налог']);
        RobokassaTax::create(['code' => 'patent', 'name' => 'Патентная СН']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
