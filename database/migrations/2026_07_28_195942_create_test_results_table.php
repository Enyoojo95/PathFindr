<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('numerical_score', 3, 1)->nullable();
            $table->decimal('verbal_score', 3, 1)->nullable();
            $table->decimal('spatial_score', 3, 1)->nullable();
            $table->decimal('interest_score', 3, 1)->nullable();
            $table->decimal('total_score', 3, 1)->nullable();
            $table->string('recommended_combination')->nullable();
            $table->json('careers')->nullable();
            $table->json('universities')->nullable();
            $table->text('motivational_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};