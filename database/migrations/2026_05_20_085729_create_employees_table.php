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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_code')->unique()->comment('Employee Code unique identifier');
            $table->string('fingerprint_code')->unique()->comment('Fingerprint Code unique identifier');
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('home_telephone')->nullable();
            $table->string('work_telephone')->nullable();
            $table->integer('blood_group_id')->nullable();
            $table->string('stable_address')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('governorate_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('children_count')->default(0);
            $table->tinyInteger('gender')->nullable();
            $table->integer('marital_status')->nullable()->comment('1: Single, 2: Married, 3: engaged , 4: Widowed , 5: Divorced');
            $table->integer('military_status')->nullable()->comment('1: Active, 2: Resigned, 3: Discharged');
            $table->date('military_start_date')->nullable();
            $table->date('military_end_date')->nullable();
            $table->string('military_weapon')->nullable();
            $table->date('military_exemption_date')->nullable();
            $table->string('military_exemption_reason')->nullable();
            $table->tinyInteger('driving_license')->default(0)->comment('1: Yes, 0: No');
            $table->string('driving_license_number')->nullable();
            $table->foreignId('religion_id')->nullable()->constrained('religions')->cascadeOnUpdate();
            $table->foreignId('qualifications_id')->nullable()->constrained('qualifications')->cascadeOnUpdate();
            $table->string('qualification_year')->nullable();
            $table->tinyInteger('graduation_grade')->nullable()->comment('1: Excellent, 2: Good, 3: Fair, 4: Poor');
            $table->string('graduation_specialization')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('hire_date_day_month_year')->nullable();
            $table->tinyInteger('employment_status')->default(1)->comment('1: Active, 0: Inactive');
            $table->foreignId('resignation_id')->nullable()->constrained('resignations')->cascadeOnUpdate();
            $table->date('resignation_date')->nullable();
            $table->string('resignation_reason')->nullable();
            $table->tinyInteger('motivation_type')->nullable()->comment('0: None, 1: Fixed, 2: Variable');
            $table->decimal('motivation_amount', 10, 2)->nullable();
            $table->tinyInteger('payment_method')->nullable()->comment('1: Cash, 2: Bank Transfer, 3: Check');
            $table->string('bank_account_number')->nullable();
            $table->tinyInteger('has_disability')->nullable()->comment('1: Yes, 0: No');
            $table->string('disability_description')->nullable();
            $table->tinyInteger('has_relative')->nullable()->comment('1: Yes, 0: No');
            $table->string('relative_description')->nullable();
            $table->string('urgent_contact_details')->nullable();
            $table->decimal('daily_work_hours', 10, 2)->nullable();
            $table->foreignId('job_id')->constrained('jobs_categories')->cascadeOnUpdate();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate();
            $table->foreignId('nationality_id')->constrained('nationalities')->cascadeOnUpdate();
            $table->string('nationality_number')->nullable();
            $table->date('nationality_expiry_date')->nullable();
            $table->string('nationality_place_of_issue')->nullable();
            $table->string('sponsor_name')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_place_of_issue')->nullable();
            $table->string('image')->nullable();
            $table->decimal('salary', 10, 2)->default(0.00)->nullable();
            $table->bigInteger('lang_id')->nullable();
            $table->integer('company_id');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->tinyInteger('fixed_shift')->default(0);
            $table->foreignId('shift_type_id')->nullable()->constrained('shifts_types')->cascadeOnUpdate();
            $table->decimal('payment_per_day', 10, 2)->nullable();
            $table->tinyInteger('has_social_insurance')->nullable()->comment('1: Yes, 0: No');
            $table->decimal('social_insurance_amount', 10, 2)->nullable();
            $table->string('social_insurance_number')->nullable();
            $table->tinyInteger('has_medical_insurance')->nullable()->comment('1: Yes, 0: No');
            $table->decimal('medical_insurance_amount', 10, 2)->nullable();
            $table->tinyInteger('fixed_allowance')->default(0);
            $table->tinyInteger('has_attendance')->default(1)->comment('1: Yes, 0: No');
            $table->tinyInteger('vacation_formula')->default(0);
            $table->tinyInteger('active_for_vacation')->default(0);
            $table->tinyInteger('has_sensitive_data')->default(0);
            $table->bigInteger('branch_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
