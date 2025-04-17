<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
     use HasFactory;

     protected $fillable = [
         'course_content_id', 'user_id', 'content', 'file_path',
         'grade', 'feedback', 'graded_by', 'submitted_at', 'graded_at'
     ];

     protected $casts = [
         'submitted_at' => 'datetime',
         'graded_at' => 'datetime',
         'grade' => 'decimal:2',
     ];

     // The content item this submission is for
     public function courseContent(): BelongsTo
     {
         return $this->belongsTo(CourseContent::class);
     }

     // The student who made the submission
     public function student(): BelongsTo // Renamed from user for clarity
     {
         return $this->belongsTo(User::class, 'user_id');
     }

     // The educator who graded the submission
     public function grader(): BelongsTo // Renamed from gradedBy for clarity
     {
         return $this->belongsTo(User::class, 'graded_by');
     }
}
