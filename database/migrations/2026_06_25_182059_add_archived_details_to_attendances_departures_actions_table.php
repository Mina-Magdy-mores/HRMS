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
            //
            if (!Schema::hasColumn('attendances_departures_actions', 'archived_status')) {
                $table->tinyInteger('archived_status')->default(0)->comment('0: not archived, 1: archived');
            }
            if (!Schema::hasColumn('attendances_departures_actions', 'archived_at')) {
                $table->timestamp('archived_at')->nullable();
            }
            if (!Schema::hasColumn('attendances_departures_actions', 'archived_by')) {
                $table->integer('archived_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances_departures_actions', function (Blueprint $table) {
            //
            if (Schema::hasColumn('attendances_departures_actions', 'archived_status')) {
                $table->dropColumn('archived_status');
            }
            if (Schema::hasColumn('attendances_departures_actions', 'archived_at')) {
                $table->dropColumn('archived_at');
            }
            if (Schema::hasColumn('attendances_departures_actions', 'archived_by')) {
                $table->dropColumn('archived_by');
            }
        });
    }
};
