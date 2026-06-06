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
            $table->decimal('employee_net_salary_after_close_for_roll_over', 15, 2)->nullable()->after('employee_net_salary');
            $table->tinyInteger('is_disbursed')->nullable()->after('employee_net_salary_after_close_for_roll_over');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employees', function (Blueprint $table) {
            $table->dropColumn('employee_net_salary_after_close_for_roll_over');
            $table->dropColumn('is_disbursed');
        });
    }
};
