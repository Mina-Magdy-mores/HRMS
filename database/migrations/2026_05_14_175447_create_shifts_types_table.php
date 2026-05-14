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
        Schema::create('shifts_types', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->nullable()->comment('1: Day Shift, 2: Night Shift');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('total_hours', 10, 2);
            $table->tinyInteger('status')->default(1);
            $table->integer('company_id');
            $table->foreignId('created_by')->constrained('admins')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('updated_by')->constrained('admins')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts_types');
    }
};
