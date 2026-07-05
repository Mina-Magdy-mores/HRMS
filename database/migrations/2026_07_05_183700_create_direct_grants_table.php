<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direct_grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnUpdate();
            $table->foreignId('finance_monthly_calendar_id')->constrained('finance_monthly_calendars')->cascadeOnUpdate();
            $table->foreignId('salary_grant_type_id')->constrained('salary_grant_types')->cascadeOnUpdate();
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('company_id');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direct_grants');
    }
};
