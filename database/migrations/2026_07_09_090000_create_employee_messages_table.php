<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignId('sender_id')->constrained('admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('message');
            $table->tinyInteger('is_read')->default(0)->comment('0: unread, 1: read');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_messages');
    }
};
