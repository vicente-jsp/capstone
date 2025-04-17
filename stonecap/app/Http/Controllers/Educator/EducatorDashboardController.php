<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducatorDashboardController extends Controller
{
    /**
     * Show the educator dashboard.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Fetch data relevant to this educator
        $courseCount = $user->managedCourses()->count();
        $recentSubmissions = \App\Models\Submission::whereIn(
                'course_content_id',
                function ($query) use ($user) {
                    $query->select('id')
                          ->from('course_contents')
                          ->whereIn('course_id', $user->managedCourses()->pluck('id'));
                }
            )
            ->whereNull('graded_at') // Example: Get ungraded submissions
            ->latest('submitted_at')
            ->take(5)
            ->with(['student', 'courseContent.course']) // Eager load relations
            ->get();


        // Add other stats as needed (e.g., enrolled students count)

        return view('educator.dashboard', compact('user', 'courseCount', 'recentSubmissions'));
    }
}