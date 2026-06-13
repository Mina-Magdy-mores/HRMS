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
        Schema::create('attendances_departures_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendances_departure_id');
            $table->foreign('attendances_departure_id', 'fk_attendances_departures_actions_attendances_departure')
                ->references('id')
                ->on('attendances_departures')
                ->onUpdate('cascade')->cascadeOnDelete();

            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_attendances_departures_actions_calendar')
                ->references('id')
                ->on('finance_monthly_calendars')
                ->onUpdate('cascade')->noActionOnDelete();

            $table->foreignId('employee_id');
            $table->foreign('employee_id', 'fk_attendances_departures_actions_employee')
                ->references('id')
                ->on('employees')
                ->onUpdate('cascade')->noActionOnDelete();
            $table->dateTime('dateTimeAction')->nullable();
            $table->enum('type', ['1', '2'])->comment('1: departure, 2: arrival');
            $table->enum('added_method', ['1', '2'])->default('1')->comment('1:automaticly, 2:manualy');
            $table->enum('is_active_with_parent', ['0', '1'])->default('0')->comment('0: No Action Made, 1: Action Made');
            $table->enum('is_action_made_on_employee', ['0', '1'])->comment('0: No Action Made, 1: Action Made');
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
        Schema::dropIfExists('attendances_departures_actions');
    }
};
