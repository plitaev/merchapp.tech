<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\PayGuest;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $permissions = [];

        //BotBranch
       $permissions = Permission::create([
            'name' => 'ViewAny:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:BotBranch',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:BotBranch',
            'guard_name' => 'web',
        ]);
        //Sending

       $permissions = Permission::create([
            'name' => 'ViewAny:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:Sending',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:Sending',
            'guard_name' => 'web',
        ]);

        //Funnel
       $permissions = Permission::create([
            'name' => 'ViewAny:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:Funnel',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:Funnel',
            'guard_name' => 'web',
        ]);
        //TelegramUnbanSchedule
       $permissions = Permission::create([
            'name' => 'ViewAny:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:TelegramUnbanSchedule',
            'guard_name' => 'web',
        ]);

        //BotUserBanSchedule
       $permissions = Permission::create([
            'name' => 'ViewAny:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:BotUserBanSchedule',
            'guard_name' => 'web',
        ]);
        //Product
       $permissions = Permission::create([
            'name' => 'ViewAny:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:Product',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:Product',
            'guard_name' => 'web',
        ]);

        //TelegramSupergroup
       $permissions = Permission::create([
            'name' => 'ViewAny:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:TelegramSupergroup',
            'guard_name' => 'web',
        ]);
    //PayGuest
       $permissions = Permission::create([
            'name' => 'ViewAny:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:PayGuest',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:PayGuest',
            'guard_name' => 'web',
        ]);

        //Pay
       $permissions = Permission::create([
            'name' => 'ViewAny:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:Pay',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:Pay',
            'guard_name' => 'web',
        ]);

        //BotMessage
       $permissions = Permission::create([
            'name' => 'ViewAny:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:BotMessage',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:BotMessage',
            'guard_name' => 'web',
        ]);

        //PaySystem
       $permissions = Permission::create([
            'name' => 'ViewAny:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:PaySystem',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:PaySystem',
            'guard_name' => 'web',
        ]);

        //TelegramSendMessageSchedule
       $permissions = Permission::create([
            'name' => 'ViewAny:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:TelegramSendMessageSchedule',
            'guard_name' => 'web',
        ]);

        //BotMessageListener
       $permissions = Permission::create([
            'name' => 'ViewAny:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:BotMessageListener',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:BotMessageListener',
            'guard_name' => 'web',
        ]);

        //BotMessageButton
       $permissions = Permission::create([
            'name' => 'ViewAny:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'View:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Create:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Update:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Delete:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Restore:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'ForceDeleteAny:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'RestoreAny:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Replicate:BotMessageButton',
            'guard_name' => 'web',
        ]);
       $permissions = Permission::create([
            'name' => 'Reorder:BotMessageButton',
            'guard_name' => 'web',
        ]);

       $role_id =  Role::where('name', 'super-admin')->first()->id;

       foreach ($permissions as $permission) {
           Role::create([
               'permission_id' => $permission,
               'role_id' => $role_id,
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
