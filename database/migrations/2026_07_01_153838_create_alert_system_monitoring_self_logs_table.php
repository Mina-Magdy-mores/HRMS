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
        Schema::create('alert_system_monitoring_self_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnUpdate();
            $table->string('action'); // e.g. 'حذف', 'تمييز', 'إلغاء تمييز'
            $table->bigInteger('target_log_id');
            $table->string('target_log_name');
            $table->text('target_log_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_system_monitoring_self_logs');
    }
};
