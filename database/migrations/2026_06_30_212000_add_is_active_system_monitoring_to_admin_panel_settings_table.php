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
            $table->tinyInteger('is_active_system_monitoring')->default(1)->after('status')->comment('1 => active, 0 => inactive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            $table->dropColumn('is_active_system_monitoring');
        });
    }
};
