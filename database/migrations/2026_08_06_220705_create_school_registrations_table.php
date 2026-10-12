<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('address');
            $table->string('country');
            $table->string('state');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('website')->nullable();
            $table->unsignedInteger('number_of_classes');
            $table->string('student_count_range');   // e.g. "50-100", "100-200"
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_registrations');
    }
};
