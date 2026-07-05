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
        Schema::create('main_salary_employee_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_settlements_calendar')
                ->references('id')
                ->on('finance_monthly_calendars')
                ->onUpdate('cascade');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate();
            $table->decimal('employee_per_day_salary', 15, 2)->nullable()->default(0)->comment('Per day salary of the employee');
            $table->decimal('working_days_number', 15, 2)->nullable()->default(0)->comment('Number of working days in the month that are not included in the main salary');
            $table->decimal('working_days_amount', 15, 2)->nullable()->default(0)->comment('Amount for the working days in the month that are not included in the main salary');
            $table->decimal('extra_working_days_number', 15, 2)->nullable()->default(0)->comment('Number of extra working days in the month that are not included in the main salary');
            $table->decimal('extra_working_days_amount', 15, 2)->nullable()->default(0)->comment('Amount for the extra working days in the month that are not included in the main salary');
            $table->decimal('absent_days_back_number', 15, 2)->nullable()->default(0)->comment('Number of absent days back');
            $table->decimal('absent_days_back_amount', 15, 2)->nullable()->default(0)->comment('Amount for the absent days back');
            $table->decimal('deducted_days_restored_number', 15, 2)->nullable()->default(0)->comment('Number of absence days restored to the employee');
            $table->decimal('deducted_days_restored_amount', 15, 2)->nullable()->default(0)->comment('Amount for the absence days restored to the employee');
            $table->decimal('different_in_salary_amount', 15, 2)->nullable()->default(0)->comment('diffrent in the main salary and the calculated salary');
            $table->decimal('bonus_amount', 15, 2)->nullable()->default(0)->comment('amount of bonus to add to the employee');
            $table->decimal('allowance_amount', 15, 2)->nullable()->default(0)->comment('amount of allowance to add to the employee');
            $table->decimal('total_amount_for_addition', 15, 2)->nullable()->default(0)->comment('total amount for addition');

            $table->decimal('absent_days_number', 15, 2)->nullable()->default(0)->comment('Number of absent days');
            $table->decimal('absent_days_amount', 15, 2)->nullable()->default(0)->comment('Amount for the absent days');
            $table->decimal('deducted_days_number', 15, 2)->nullable()->default(0)->comment('Number of deducted days');
            $table->decimal('deducted_days_amount', 15, 2)->nullable()->default(0)->comment('Amount for the deducted days');
            $table->decimal('salary_deduction_amount', 15, 2)->nullable()->default(0)->comment('Amount for the salary deduction');
            $table->decimal('others_salary_deduction_amount', 15, 2)->nullable()->default(0)->comment('Amount for the others salary deduction');
            $table->decimal('medical_insurance_deduction_amount', 15, 2)->nullable()->default(0)->comment('Amount for the medical insurance deduction');
            $table->decimal('monthly_loan_deduction_amount', 15, 2)->nullable()->default(0)->comment('Amount for the monthly loan deduction');
            $table->decimal('permanent_loan_deduction_amount', 15, 2)->nullable()->default(0)->comment('Amount for the monthly loan deduction');
            $table->decimal('penalty_deduction_amount', 15, 2)->nullable()->default(0)->comment('amount of penalty to deduct from the employee');
            $table->decimal('total_amount_for_deduction', 15, 2)->nullable()->default(0)->comment('total amount for deduction');

            $table->decimal('final_total_amount', 15, 2)->nullable()->default(0)->comment('final total amount for the employee');

            $table->integer('is_archived')->default(0)->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
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
        Schema::dropIfExists('main_salary_employee_settlements');
    }
};
