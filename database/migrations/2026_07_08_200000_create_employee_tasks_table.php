<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create table
        Schema::create('employee_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate()->cascadeOnDelete();
            $table->tinyInteger('is_completed')->default(0)->comment('0: not completed, 1: completed');
            $table->tinyInteger('is_archived')->default(0)->comment('0: active, 1: archived');
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('archived_at')->nullable();
            $table->integer('company_id');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop table
        Schema::dropIfExists('employee_tasks');
    }
};
