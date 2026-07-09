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
        Schema::table('employee_tasks', function (Blueprint $table) {
            $table->text('employee_reply')->nullable()->comment('رد الموظف على المهمة');
            $table->dateTime('employee_replied_at')->nullable()->comment('تاريخ رد الموظف');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_tasks', function (Blueprint $table) {
            $table->dropColumn(['employee_reply', 'employee_replied_at']);
        });
    }
};
