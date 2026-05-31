<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code')->unique()->nullable();
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('language')->default('en'); 
            $table->string('department')->nullable();
            $table->string('department_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->foreignId('template_course_id')->nullable()->constrained('courses')->nullOnDelete();

            $table->enum('access_type', ['public', 'private', 'restricted'])->default('private');
            $table->boolean('is_registered')->default(false);    
            $table->boolean('is_allowed')->default(true);        

            $table->enum('subscription_type', ['allowed', 'teacher_only'])->default('allowed');
            $table->boolean('allow_unsubscribe')->default(true); 
            $table->integer('storage_limit_mb')->nullable();     

            $table->boolean('is_special')->default(false);       

            $table->json('tags')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};