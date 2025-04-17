<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Show details of a specific course the student is enrolled in
    public function show(Course $course)
    {
        /** @var \App\Models\User $user */ // <-- Add this PHPDoc hint
        $user = Auth::user();

        // Authorization: Check if the student is enrolled
        // This uses the `enrolledCourses` relationship defined in the User model
        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Eager load visible content, maybe check for submissions
        $course->load(['contents' => function ($query) {
            $query->where('is_visible', true)
                  ->where(function($q) { // Content availability dates
                       $q->whereNull('available_from')
                         ->orWhere('available_from', '<=', now());
                  })
                  ->orderBy('order');
        }]);

        // You might also want to fetch the student's submissions for this course content
        // to display status (submitted, graded etc.) - requires more complex querying or view logic

        return view('student.courses.show', compact('course'));
    }
}