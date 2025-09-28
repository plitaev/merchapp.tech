<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\ProdamusNpdIncomeType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ProdamusNpdIncomeType::create(['code' => 'FROM_INDIVIDUAL', 'name' => 'Физическое лицо']);
        ProdamusNpdIncomeType::create(['code' => 'FROM_LEGAL_ENTITY', 'name' => 'Юридическое лицо']);
        ProdamusNpdIncomeType::create(['code' => 'FROM_FOREIGN_AGENCY', 'name' => 'Иностранная организация']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
