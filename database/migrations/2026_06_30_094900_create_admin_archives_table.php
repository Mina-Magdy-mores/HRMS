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
        Schema::create('admin_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->comment('id الأدمن الأصلي');
            $table->string('action')->comment('update أو delete');

            // بيانات الأدمن القديمة
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('username');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('national_id')->nullable();
            $table->string('gender')->nullable();
            $table->text('bio')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status');
            $table->date('date')->nullable();
            $table->integer('company_id');

            // من أرشف
            $table->unsignedBigInteger('archived_by');
            $table->foreign('archived_by')->references('id')->on('admins')->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_archives');
    }
};
