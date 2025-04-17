<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
     /**
     * Show the student dashboard (list of enrolled courses).
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get courses the student is enrolled in
        $enrolledCourses = $user->enrolledCourses()->where('is_active', true) // Only show active courses
                              ->orderBy('title')
                              ->get();

        // Fetch upcoming deadlines? Recent grades? Announcements?

        return view('student.dashboard', compact('user', 'enrolledCourses'));
    }
}