<?php
use App\Models\Core\ModelHasPermission;
use App\Models\Core\ModelHasRole;
use App\Models\Core\UserHasPermission;
use App\Models\Core\UserHasRole;
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
        Schema::create('user_has_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type',255);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('user_has_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('model_type',255);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        $model_has_permissions = ModelHasPermission::get();

        if($model_has_permissions) {
            foreach ($model_has_permissions as $model_has_permission) {

                $user_has_permission = new UserHasPermission();

                $user_has_permission->permission_id = $model_has_permission['permission_id'];

                $user_has_permission->model_type = $model_has_permission['model_type'];
                $user_has_permission->user_id = $model_has_permission['model_id'];
                $user_has_permission->save();

            }
        }

        $model_has_roles = ModelHasRole::get();

        if($model_has_roles) {
            foreach ($model_has_roles as $model_has_role) {

                $user_has_role = new UserHasRole();

                $user_has_role->role_id = $model_has_role['role_id'];

                $user_has_role->model_type = $model_has_role['model_type'];
                $user_has_role->user_id = $model_has_role['model_id'];
                $user_has_role->save();

            }
        }

        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permission');
    }
};