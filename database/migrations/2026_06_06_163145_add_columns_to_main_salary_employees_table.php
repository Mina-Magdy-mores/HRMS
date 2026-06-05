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
            $table->decimal('employee_deductions_days_counter', 15, 2)->nullable()->default(0)->comment('Number of deductions days of the employee')->after('employee_total_overtime_payment_per_day');
            $table->decimal('employee_deductions_payment_total', 15, 2)->nullable()->default(0)->comment('Payment per deductions day of the employee')->after('employee_deductions_days_counter');
            $table->decimal('employee_additions_days_counter', 15, 2)->nullable()->default(0)->comment('Number of allowance days of the employee')->after('employee_deductions_payment_total');
            $table->decimal('employee_additions_payment_total', 15, 2)->nullable()->default(0)->comment('Payment per allowance day of the employee')->after('employee_additions_days_counter');
            $table->decimal('employee_absences_days_counter', 15, 2)->nullable()->default(0)->comment('Number of absence days of the employee')->after('employee_additions_payment_total');
            $table->decimal('employee_absences_payment_total', 15, 2)->nullable()->default(0)->comment('Payment per absence day of the employee')->after('employee_absences_days_counter');

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employees', function (Blueprint $table) {
            $table->dropColumn('employee_deductions_days_counter');
            $table->dropColumn('employee_deductions_payment_total');
            $table->dropColumn('employee_additions_days_counter');
            $table->dropColumn('employee_additions_payment_total');
            $table->dropColumn('employee_absences_days_counter');
            $table->dropColumn('employee_absences_payment_total');
        });
    }
};
