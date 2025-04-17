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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_content_id')->constrained('course_contents')->onDelete('cascade'); // Link to assignment/quiz
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Student submitting
            $table->text('content')->nullable(); // Student's text submission
            $table->string('file_path')->nullable(); // Student's file submission
            $table->decimal('grade', 5, 2)->nullable(); // e.g., 85.50
            $table->text('feedback')->nullable(); // Educator's feedback
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null'); // Educator who graded
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps(); // Includes created_at (same as submitted_at initially) and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
