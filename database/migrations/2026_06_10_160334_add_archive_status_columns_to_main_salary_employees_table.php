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
            $table->tinyInteger('archive_status_type')->after('employee_net_salary_after_close_for_roll_over')->nullable()->comment('1 = دائن, 2 = مدين, 3 = صافي');
            $table->decimal('archive_settlement_amount', 10, 2)->after('archive_status_type')->default(0.00)->comment('المبلغ المدفوع أو المحصل عند الأرشفة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employees', function (Blueprint $table) {
            $table->dropColumn(['archive_status_type', 'archive_settlement_amount']);
        });
    }
};
