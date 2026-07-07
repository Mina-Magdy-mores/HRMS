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
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            $table->tinyInteger('is_allowed_to_pull_salary_variables_from_fingerprint')->default(1)
                ->comment('1 automaticly, 0 manually');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            $table->dropColumn('is_allowed_to_pull_salary_variables_from_fingerprint');
        });
    }
};
