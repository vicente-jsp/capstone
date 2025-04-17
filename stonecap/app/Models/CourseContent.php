<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'description', 'type', 'content_data',
        'file_path', 'order', 'is_visible', 'available_from', 'due_date'
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'due_date' => 'datetime',
        'is_visible' => 'boolean',
        // 'content_data' => 'array', // If storing JSON
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Submissions related to this content (if it's an assignment/quiz)
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
