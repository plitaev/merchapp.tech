<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Core\Permission;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $new = Permission::create(['name' => 'Delete:User', 'guard_name' => 'web']);
        DB::table('role_has_permissions')->insertOrIgnore(['role_id' => 1, 'permission_id' => $new->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
