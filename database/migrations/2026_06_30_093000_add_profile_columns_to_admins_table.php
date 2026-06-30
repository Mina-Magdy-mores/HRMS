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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('image')->nullable()->after('email');
            $table->string('phone')->nullable()->after('image');
            $table->string('address')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('national_id')->nullable()->unique()->after('birth_date');
            $table->string('gender')->nullable()->after('national_id'); // male / female
            $table->text('bio')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['image', 'phone', 'address', 'birth_date', 'national_id', 'gender', 'bio']);
        });
    }
};
