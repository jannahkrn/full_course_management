<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('course_sessions')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['document', 'video', 'link', 'text'])->default('document');
            $table->string('file_path')->nullable();
            $table->string('file_url')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size_kb')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['session_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};