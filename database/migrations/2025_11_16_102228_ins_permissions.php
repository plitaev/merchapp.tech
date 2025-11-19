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
        //GetcourseWebhook
        $permissions []= Permission::create([
            'name' => 'ViewAny:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'View:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Create:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Update:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Delete:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Restore:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'ForceDeleteAny:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'RestoreAny:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Replicate:GetcourseWebhook',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Reorder:GetcourseWebhook',
            'guard_name' => 'web',
        ]);

        foreach ($permissions as $permission) {

            DB::table('role_has_permissions')->insert([
                'permission_id' => $permission->id,
                'role_id' => 1,
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
