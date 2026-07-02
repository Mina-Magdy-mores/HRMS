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
        Schema::table('admins', function (Blueprint $table) {
            $table->tinyInteger('is_master_admin')->default(0);
            $table->foreignId('permission_role_id')->nullable();
            $table->foreign('permission_role_id', 'fk_admins_permission_role')
                ->references('id')->on('permission_roles')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign('fk_admins_permission_role');
            $table->dropColumn('permission_role_id');
            $table->dropColumn('is_master_admin');
        });
    }
};
