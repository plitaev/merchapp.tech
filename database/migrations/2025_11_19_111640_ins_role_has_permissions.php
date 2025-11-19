<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $permissions_view = Permission::select('id')->where("name", "LIKE", "View:%")->get();

        foreach ($permissions_view as $permission) {
            $role_has_permissions = DB::table('role_has_permissions')->select('permission_id', 'role_id')->where('role_id', 2)->toArray();
            if(!array_search($permission->id, $role_has_permissions->permission_id)) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => 2,
                ]);
            }
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
