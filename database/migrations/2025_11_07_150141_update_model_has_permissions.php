<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\PayGuest;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        for ($i = 146; $i < 280; $i++) {

            DB::table('model_has_permissions')->insert([
                'permission_id' => $i,
                'model_type' => 'App\Models\Core\User',
                'model_id' => 1,
            ]);
            DB::table('model_has_permissions')->insert([
                'permission_id' => $i,
                'model_type' => 'App\Models\Core\User',
                'model_id' => 3,
            ]);

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
