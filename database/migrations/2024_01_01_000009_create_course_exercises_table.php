<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('course_sessions')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['quiz', 'assignment', 'exam'])->default('assignment');
            $table->integer('duration_minutes')->nullable();
            $table->decimal('max_score', 5, 2)->default(100);
            $table->decimal('passing_score', 5, 2)->default(60);
            $table->timestamp('open_at')->nullable();
            $table->timestamp('close_at')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_exercises');
    }
};