<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('school_name')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'abandoned'])->default('active');
            $table->unsignedInteger('current_question')->default(0);
            $table->unsignedInteger('time_remaining')->default(1800); // seconds
            $table->timestamp('started_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('last_heartbeat')->nullable();
            $table->string('paused_by')->nullable();
            $table->text('pause_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
