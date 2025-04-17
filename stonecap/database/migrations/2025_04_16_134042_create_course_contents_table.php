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
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['resource', 'assignment', 'quiz', 'forum_topic', 'announcement'])->default('resource');
            $table->text('content_data')->nullable(); // Could store JSON, markdown, external link, quiz ID etc.
            $table->string('file_path')->nullable(); // For uploaded files
            $table->integer('order')->default(0); // For sequencing content
            $table->boolean('is_visible')->default(true);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('due_date')->nullable(); // For assignments/quizzes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_contents');
    }
};
