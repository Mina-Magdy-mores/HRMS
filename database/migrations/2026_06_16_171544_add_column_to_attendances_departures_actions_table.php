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
        Schema::table('attendances_departures_actions', function (Blueprint $table) {
             $table->foreignId('attendance_departure_actions_excel_id')->nullable()->after('is_action_made_on_employee');
            $table->foreign('attendance_departure_actions_excel_id', 'fk_attendance_actions_excels')
            ->references('id')
            ->on('attendance_departure_actions_excels')
            ->onUpdate('cascade')->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_departures_actions', function (Blueprint $table) {
            $table->dropForeign('fk_attendance_actions_excels');
            $table->dropColumn('attendance_departure_actions_excel_id');
        });
    }
};
