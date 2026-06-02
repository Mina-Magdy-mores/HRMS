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
        Schema::create('main_salary_employee_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_salary_employee_id')->constrained('main_salary_employees')->cascadeOnUpdate();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate();
            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_allowances_calendar')
                ->references('id')
                ->on('finance_monthly_calendars')
                ->onUpdate('cascade');
            $table->foreignId('allowance_type_id')->constrained('allowance_types')->cascadeOnUpdate();
            $table->decimal('amount', 10, 2);
            $table->integer('is_archived')->default(0)->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->dateTime('archived_at')->nullable();
            $table->integer('is_auto')->default(0);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('main_salary_employee_allowances');
    }
};
