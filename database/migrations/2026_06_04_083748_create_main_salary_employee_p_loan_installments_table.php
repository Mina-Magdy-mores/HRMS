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
        Schema::create('main_salary_employee_p_loan_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_salary_employee_p_loan_id');
            $table->foreign('main_salary_employee_p_loan_id', 'fk_loan_installment_loan')
                ->references('id')->on('main_salary_employee_p_loans')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('main_salary_employee_id')->nullable();
            $table->foreign('main_salary_employee_id', 'fk_loan_installment_employee')
                ->references('id')->on('main_salary_employees')->cascadeOnUpdate();
            $table->decimal('amount', 10, 2)->comment('loan amount');
            $table->decimal('installment_amount_monthly', 10, 2)->comment('amount of each installment monthly');
            $table->string('next_installment_year_and_month')->comment('year and month of next installment');
            $table->enum('installment_status', ['1', '0', '2'])->default('0')->comment('0 is pending for installment,1 is paid for installment on salary,2 is paid for installment cash');
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
        Schema::dropIfExists('main_salary_employee_p_loan_installments');
    }
};
