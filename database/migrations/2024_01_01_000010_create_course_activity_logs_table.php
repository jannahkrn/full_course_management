<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action'); 
            $table->morphs('loggable'); 
            $table->json('meta')->nullable();
            $table->timestamp('accessed_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'course_id']);
            $table->index(['user_id', 'accessed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_activity_logs');
    }
};