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
            $table->decimal('after_minute_quarter_day_cut', 10, 2)->default(0)->comment('بعد كم عدد ايام من مجموع الحضور او الانصراف مبكر نخصم ربع يوم')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            $table->decimal('after_minute_quarter_day_cut', 10, 2)->default(0)->comment('بعد كم عدد دقيقة من مجموع الحضور او الانصراف مبكر نخصم ربع يوم')->change();
        });
    }
};
