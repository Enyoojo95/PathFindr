<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->decimal('numerical_score', 5, 2)->nullable()->change();
            $table->decimal('verbal_score',    5, 2)->nullable()->change();
            $table->decimal('spatial_score',   5, 2)->nullable()->change();
            $table->decimal('interest_score',  5, 2)->nullable()->change();
            $table->decimal('total_score',     5, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->decimal('numerical_score', 3, 1)->nullable()->change();
            $table->decimal('verbal_score',    3, 1)->nullable()->change();
            $table->decimal('spatial_score',   3, 1)->nullable()->change();
            $table->decimal('interest_score',  3, 1)->nullable()->change();
            $table->decimal('total_score',     3, 1)->nullable()->change();
        });
    }
};
