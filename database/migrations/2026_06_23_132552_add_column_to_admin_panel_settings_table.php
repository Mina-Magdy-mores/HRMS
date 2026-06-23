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
            $table->integer('after_shift_max_extra_hours')->default(4)->comment('أقصى عدد ساعات عمل إضافية بعد انتهاء الشيفت لتقفيل البصمة كـانصراف');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_panel_settings', function (Blueprint $table) {
            $table->dropColumn('after_shift_max_extra_hours');
        });
    }
};
