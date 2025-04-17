<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // List courses managed by the logged-in educator
    public function index()
    {
        /** @var \App\Models\User $user */ // <-- Add this PHPDoc hint
        $user = Auth::user();
        
        $courses = Auth::user()->managedCourses()->latest()->paginate(15);
        return view('educator.courses.index', compact('courses'));
    }

    // Show details and content management interface for a specific course
    public function show(Course $course)
    {
        /** @var \App\Models\User $user */ // <-- Add hint here too
        $user = Auth::user(); // Even if only used for the check like below
        // Authorization: Ensure the logged-in educator owns this course
        if ($course->educator_id !== Auth::id()) {
             // Optional check if admin also allowed: && !Auth::user()->isAdmin()
            abort(403, 'You do not have permission to view this course.');
        }

        // Eager load content, maybe students
        $course->load('contents'); // Add .students if needed on this view

        return view('educator.courses.show', compact('course'));
    }

    // Note: Create/Edit/Delete of COURSES themselves might be admin-only
    // or educators could create their own - depends on requirements.
    // Add store/update/destroy methods here if educators can manage courses fully.
    // Make sure to set 'educator_id' => Auth::id() when creating.
}