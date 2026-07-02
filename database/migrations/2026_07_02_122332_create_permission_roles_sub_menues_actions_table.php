<?php

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
        Schema::create('permission_roles_sub_menues_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_roles_sub_menue_id');
            $table->foreign('permission_roles_sub_menue_id', 'fk_permisssion_roles_sub_menues_actions_role_sub_menu')
                ->references('id')->on('permission_roles_sub_menues')->cascadeOnUpdate()->cascadeOnDelete();



            $table->foreignId('permission_sub_menu_action_id');
            $table->foreign('permission_sub_menu_action_id', 'fk_permisssion_roles_sub_menues_actions_sub_menu_action')
                ->references('id')->on('permission_sub_menues_actions')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('permission_role_id');
            $table->foreign('permission_role_id', 'fk_permission_roles_sub_menues_actions_permission_role')
                ->references('id')->on('permission_roles')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('added_by');
            $table->foreign('added_by', 'fk_permission_roles_sub_menues_actions_added_by')
                ->references('id')->on('admins')->cascadeOnUpdate();

            $table->foreignId('updated_by')->nullable();
            $table->foreign('updated_by', 'fk_permission_roles_sub_menues_actions_updated_by')
                ->references('id')->on('admins')->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_roles_sub_menues_actions');
    }
};
