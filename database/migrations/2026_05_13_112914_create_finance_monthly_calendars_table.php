<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_monthly_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financeCalendar_id')->constrained('finance_calendars')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('number_of_days');
            $table->string('year_and_month');
            $table->integer('finance_yr');
            $table->foreignId('month_id')->constrained('months')->cascadeOnUpdate();
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(0)->comment('واحد مفعل - صفر معطل');
            $table->date('start_date_for_calculation');
            $table->date('end_date_for_calculation');
            $table->integer('company_id');
            $table->foreignId('added_by')
                ->constrained('admins')
                ->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()
                ->nullable()
                ->constrained('admins')
                ->cascadeOnUpdate();
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
