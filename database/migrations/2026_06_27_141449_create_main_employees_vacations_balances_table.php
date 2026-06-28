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
        Schema::create('main_employees_vacations_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('year_and_month', 8)->nullable()->default('')->comment('Year and month for the salary record');
            $table->integer('financial_year')->nullable()->default(0)->comment('Financial year for the salary record');
            $table->decimal('carryover_from_previous_month', 10, 2)->nullable()->default(0)->comment('carryover from previous month vacations balance');
            $table->decimal('current_month_balance', 10, 2)->nullable()->default(0)->comment('current month vacations balance');
            $table->decimal('total_available_balance', 10, 2)->nullable()->default(0)->comment('total available balance');
            $table->decimal('spent_balance', 10, 2)->nullable()->default(0)->comment('spent vacations balance');
            $table->decimal('remaining_net_balance', 10, 2)->nullable()->default(0)->comment('remaining vacations balance');

            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->tinyInteger('is_archived')->nullable()->default(0)->comment('Status of the archived employee');
            $table->dateTime('archived_at')->nullable()->comment('Date when the employee was archived');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->integer('company_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_employees_vacations_balances');
    }
};
