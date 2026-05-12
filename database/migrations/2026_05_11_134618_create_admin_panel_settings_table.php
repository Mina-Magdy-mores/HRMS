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
        Schema::create('admin_panel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->tinyInteger('status')->default(1)->comment('واحد مفعل - صفر معطل');
            $table->string('image')->nullable();
            $table->string('phone');
            $table->string('address');
            $table->string('email');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('company_id');
            $table->decimal('after_minute_calculate_delay', 10, 2)->default(0)->comment('بعد كم عدد دقيقة نحسب تاخير حضور');
            $table->decimal('after_minute_calculate_early_departure', 10, 2)->default(0)->comment('بعد كم عدد دقيقة نحسب انصراف مبكر');
            $table->decimal('after_minute_quarter_day_cut', 10, 2)->default(0)->comment('بعد كم عدد دقيقة من مجموع الحضور او الانصراف مبكر نخصم ربع يوم');
            $table->decimal('after_days_half_day_cut', 10, 2)->default(0)->comment('بعد كم مرة تاخير او انصراف مبكر نخصم نص يوم');
            $table->decimal('after_days_allday_day_cut', 10, 2)->default(0)->comment('بعد كم مرة تاخير او انصراف مبكر نخصم يوم كامل');
            $table->decimal('monthly_vacation_balance', 10, 2)->default(0)->comment('رصيد الاجازات الشهرية');
            $table->decimal('after_days_begin_vacation', 10, 2)->default(0)->comment('بعد كام يوم ينزل للموظف رصيد الاجازات الشهرية');
            $table->decimal('first_balance_begin_vacation', 10, 2)->default(0)->comment('رصيد الاجازات الأولي عند بدء العمل');
            $table->decimal('sanctions_value_first_absence', 10, 2)->default(0)->comment('قيمه خصم الايام بعد اول مرة غياب بدون اذن');
            $table->decimal('sanctions_value_second_absence', 10, 2)->default(0)->comment('قيمه خصم الايام بعد ثاني مرة غياب بدون اذن');
            $table->decimal('sanctions_value_third_absence', 10, 2)->default(0)->comment('قيمه خصم الايام بعد ثالث مرة غياب بدون اذن');
            $table->decimal('sanctions_value_fourth_absence', 10, 2)->default(0)->comment('قيمه خصم الايام بعد رابع مرة غياب بدون اذن');







            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_panel_settings');
    }
};
