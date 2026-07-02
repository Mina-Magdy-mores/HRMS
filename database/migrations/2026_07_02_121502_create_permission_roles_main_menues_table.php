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
        Schema::create('permission_roles_main_menues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_role_id')->constrained('permission_roles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('permission_main_menu_id')->constrained('permission_main_menues')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_roles_main_menues');
    }
};
