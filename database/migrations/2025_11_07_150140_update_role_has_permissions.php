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

        $current_permissions = DB::table('role_has_permissions')->select('permission_id')->where('role_id', 1)->groupBy('permission_id')->pluck('permission_id')->toArray();
        $new_permissions = DB::table('permissions')->select('id')->whereNotIn('id', $current_permissions)->groupBy('id')->pluck('id')->toArray();

        foreach ($new_permissions as $permission) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission->id,
                'role_id' => 1
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
