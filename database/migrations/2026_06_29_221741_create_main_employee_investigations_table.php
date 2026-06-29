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
        Schema::create('main_employee_investigations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate();
            $table->foreignId('finance_monthly_calendar_id');
            $table->foreign('finance_monthly_calendar_id', 'fk_investigations_calendar')
                ->references('id')
                ->on('finance_monthly_calendars')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('is_auto')->default(0);
            $table->text('description')->nullable();
            $table->integer('is_archived')->default(0)->nullable()->comment('as approved');
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->dateTime('archived_at')->nullable();
            $table->integer('company_id');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_employee_investigations');
    }
};
