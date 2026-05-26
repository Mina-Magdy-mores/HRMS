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
            Schema::create('finance_calendars', function (Blueprint $table) {
                $table->id();
                $table->integer('finance_yr')->comment('كود السنة المالية');
                $table->string('finance_yr_desc');
                $table->date('start_date');
                $table->date('end_date');
                $table->tinyInteger('status')->default(0)->comment('واحد مفعل - صفر معطل - اتنين مغلق و مؤرشف');
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
        Schema::dropIfExists('finance_calendars');
    }
};
