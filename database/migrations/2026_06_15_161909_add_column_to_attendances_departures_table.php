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
        Schema::table('attendances_departures', function (Blueprint $table) {
            $table->date('day_of_finger_print')->nullable()->comment('تاريخ يوم سحب البصمه الفعلى');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_departures', function (Blueprint $table) {
            $table->dropColumn('day_of_finger_print');
        });
    }
};
