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
        Schema::create('finance_monthly_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_calendar_id')->constrained('finance_calendars')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('number_of_days');
            $table->string('year_and_month');
            $table->integer('finance_yr');
            $table->integer('month_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(0)->comment('واحد مفعل - صفر معطل');
            $table->date('start_date_for_calculation');
            $table->date('end_date_for_calculation');
            $table->integer('company_id');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_monthly_calendars');
    }
};
