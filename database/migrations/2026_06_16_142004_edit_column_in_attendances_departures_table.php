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
            $table->decimal('attendance_delay', 10, 2)->default(0)->comment('attendance delay in mins')->change();
            $table->decimal('early_departure', 10, 2)->default(0)->comment('early departure in mins')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_departures', function (Blueprint $table) {
            $table->enum('attendance_delay', ['0', '1'])->comment('0: No Delay, 1: Delay')->change();
            $table->enum('early_departure', ['0', '1'])->comment('0: No Early Departure, 1: Early Departure')->change();
        });
    }
};
