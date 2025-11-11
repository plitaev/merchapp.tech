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
        //BotUser
        $permissions []= Permission::create([
            'name' => 'ViewAny:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'View:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Create:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Update:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Delete:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Restore:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'ForceDeleteAny:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'RestoreAny:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Replicate:BotUser',
            'guard_name' => 'web',
        ]);
        $permissions []= Permission::create([
            'name' => 'Reorder:BotUser',
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
