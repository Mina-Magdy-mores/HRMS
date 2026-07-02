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
        Schema::create('permission_sub_menues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_main_menu_id')->constrained('permission_main_menues')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('permission_sub_menues');
    }
};
