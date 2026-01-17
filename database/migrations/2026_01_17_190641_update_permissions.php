<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Core\Permission;
use App\Models\Core\RoleHasPermission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $p = Permission::create(['name' => 'ViewAny::Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'View:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 2]);

        $p = Permission::create(['name' => 'Create:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'Update:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'Delete:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p =  Permission::create(['name' => 'Restore:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'ForceDelete:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'ForceDeleteAny:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'RestoreAny:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'Replicate:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);

        $p = Permission::create(['name' => 'Reorder:Content','guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' => $p->id, 'role_id' => 1]);



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
