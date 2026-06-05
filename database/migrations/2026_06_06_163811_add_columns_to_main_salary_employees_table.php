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
        Schema::table('main_salary_employees', function (Blueprint $table) {
            $table->decimal('employee_total_allowance', 15, 2)->nullable()->default(0)->comment('Total additions of the employee')->after('employee_total_bonus');
            $table->decimal('employee_total_deduction_type', 15, 2)->nullable()->default(0)->comment('Total deductions of the employee')->after('employee_total_allowance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employees', function (Blueprint $table) {
            $table->dropColumn('employee_total_allowance');
            $table->dropColumn('employee_total_deduction_type');
        });
    }
};
