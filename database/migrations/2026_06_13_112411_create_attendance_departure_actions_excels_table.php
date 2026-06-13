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
        Schema::create('attendance_departure_actions_excels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_attendance_excels_calendar')
            ->references('id')
            ->on('finance_monthly_calendars')
            ->onUpdate('cascade')->noActionOnDelete();

            $table->foreignId('employee_id');
            $table->foreign('employee_id', 'fk_attendance_excels_employee')
            ->references('id')
            ->on('employees')
            ->onUpdate('cascade')->noActionOnDelete();

            $table->dateTime('dateTimeAction');
            $table->enum('type', ['1', '2'])->comment('1: departure, 2: arrival');
            
            $table->foreignId('main_salary_employee_id')->nullable();
            $table->foreign('main_salary_employee_id', 'fk_attendance_excels_main_salary_employee')
            ->references('id')
            ->on('main_salary_employees')
            ->onUpdate('cascade')->noActionOnDelete();
            
            $table->integer('company_id');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_departure_actions_excels');
    }
};
