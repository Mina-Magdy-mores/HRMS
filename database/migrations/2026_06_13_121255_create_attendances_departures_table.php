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
        Schema::create('attendances_departures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_attendance_departures_calendar')
                ->references('id')
                ->on('finance_monthly_calendars')
                ->onUpdate('cascade')->noActionOnDelete();

            $table->foreignId('employee_id');
            $table->foreign('employee_id', 'fk_attendance_departures_employee')
                ->references('id')
                ->on('employees')
                ->onUpdate('cascade')->noActionOnDelete();

            $table->decimal('shift_hours', 8, 2)->nullable();
            $table->enum('status_move', ['1', '2'])->nullable()->comment('1: checkIn, 2: checkOut');

            $table->date('checkInDate')->nullable();
            $table->date('checkOutDate')->nullable();
            $table->time('checkInTime')->nullable();
            $table->time('checkOutTime')->nullable();
            $table->dateTime('checkInDateTime')->nullable();
            $table->dateTime('checkOutDateTime')->nullable();
            $table->string('variables')->nullable();
            $table->enum('attendance_delay', ['0', '1'])->comment('0: No Delay, 1: Delay');
            $table->enum('early_departure', ['0', '1'])->comment('0: No Early Departure, 1: Early Departure');
            $table->string('approved_attendance_delay_early_departure')->nullable()->comment('approved_attendance_delay_early_departure');
            $table->decimal('total_hours', 8, 2)->nullable()->default(0)->comment('total_hours');
            $table->decimal('absence_hours', 8, 2)->nullable()->default(0)->comment('absence_hours');
            $table->decimal('overtime_hours', 8, 2)->nullable()->default(0)->comment('overtime_hours');
            $table->enum('is_action_made_on_employee', ['0', '1'])->comment('0: No Action Made, 1: Action Made');
            $table->integer('is_archived')->default(0)->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->dateTime('archived_at')->nullable();
            $table->bigInteger('vacation_id')->nullable();
            $table->foreignId('occasion_id')->nullable()->constrained('occasions')->cascadeOnUpdate();
            $table->tinyInteger('cutting_days')->nullable()->comment('0: No Cutting, 0.25: quarter day, 0.5: half day, 1: full day');
            $table->string('year_and_month', 10)->nullable()->default('')->comment('Year and month for the salary record');
            $table->foreignId('employee_branch_id')->constrained('branches')->onUpdate('cascade')->comment('ID of the branch');
            $table->tinyInteger('employee_status')->comment('Status of the employee');
            $table->foreignId('main_salary_employee_id')->nullable();
            $table->foreign('main_salary_employee_id', 'fk_attendance_departures_main_salary_employee')
                ->references('id')
                ->on('main_salary_employees')
                ->onUpdate('cascade')->noActionOnDelete();
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
        Schema::dropIfExists('attendances_departures');
    }
};
