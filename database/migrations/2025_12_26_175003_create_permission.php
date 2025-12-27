<?php

use App\Models\Core\Permission;
use App\Models\Core\RoleHasPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table('role_has_permissions', function (Blueprint $table) {
            $table->timestamp('created_at')->after('role_id');
            $table->timestamp('updated_at')->after('created_at');
        });

        $new_id = Permission::create(['name' => 'ViewAny:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'View:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '2']);
        $new_id = Permission::create(['name' => 'Create:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'Update:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'Delete:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'Restore:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'ForceDelete:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'ForceDeleteAny:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'RestoreAny:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'Replicate:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);
        $new_id = Permission::create(['name' => 'Reorder:User',
            'guard_name' => 'web']);
        RoleHasPermission::create(['permission_id' =>  $new_id->id,
            'role_id' => '1']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission');
    }
};

