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
        Schema::create('alert_system_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('content');
            $table->foreignId('alert_module_id')->constrained('alert_modules')->cascadeOnUpdate();
            $table->foreignId('alert_move_type_id')->constrained('alert_move_types')->cascadeOnUpdate();
            $table->bigInteger('foreign_key_table_td')->nullable();
            $table->bigInteger('employee_id')->nullable();
            $table->tinyInteger('is_important')->default(0)->comment('0 => un_important, 1 => important');
            $table->tinyInteger('is_active')->default(1);
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->integer('company_id');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_system_monitorings');
    }
};
