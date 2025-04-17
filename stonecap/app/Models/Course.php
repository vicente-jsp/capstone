<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'educator_id', 'theme_color', 'is_active'];

    // Educator who manages the course
    public function educator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'educator_id');
    }

    // Students enrolled in the course
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_enrollments')
                    ->wherePivot('role_in_course', 'student'); // Example if using role_in_course
                    // Or simply filter by User role if simpler: ->where('role', 'student') on the User model side is often easier
    }

     // All users enrolled (could include educators if they can also enroll)
     public function enrolledUsers(): BelongsToMany
     {
         return $this->belongsToMany(User::class, 'course_enrollments');
     }


    // Content within the course
    public function contents(): HasMany
    {
        return $this->hasMany(CourseContent::class)->orderBy('order'); // Order by sequence
    }

     // Enrollments relationship (for managing pivot data)
     public function enrollments(): HasMany
     {
         return $this->hasMany(CourseEnrollment::class);
     }
}
