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
                        $table->decimal('motivation_amount', 15, 2)->nullable()->default(0)->after('variable_allowance');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('main_salary_employees', function (Blueprint $table) {
            $table->dropColumn('motivation_amount');
        });
    }
};
