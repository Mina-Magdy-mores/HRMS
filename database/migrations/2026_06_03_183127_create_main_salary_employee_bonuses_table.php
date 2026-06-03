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
        Schema::create('main_salary_employee_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_salary_employee_id');
            $table->foreign('main_salary_employee_id', 'fk_bonus_main_salary_employee')
                ->references('id')
                ->on('main_salary_employees')
                ->onUpdate('cascade');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate();
            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_bonus_monthly_calendar')
                ->references('id')
                ->on('finance_monthly_calendars')
                ->onUpdate('cascade');
            $table->foreignId('bonus_id');
            $table->foreign('bonus_id', 'fk_bonus_id')
                ->references('id')
                ->on('bonuses')
                ->onUpdate('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('is_archived')->default(0)->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->dateTime('archived_at')->nullable();
            $table->integer('is_auto')->default(0)->comment('0 => no, 1 => yes');
            $table->tinyInteger('status')->default(1)->comment('1 => active, 0 => inactive');
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
        Schema::dropIfExists('main_salary_employee_bonuses');
    }
};
