<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Import the base Controller
use Illuminate\Http\Request;
use App\Models\User;     // Import User model to count users
use App\Models\Course;   // Import Course model to count courses
use Illuminate\Support\Facades\View; // Import View facade (optional, helper `view()` is common)

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // --- Fetch Data for the Dashboard ---

        // Example: Get counts for display widgets
        $userCount = User::count();
        $studentCount = User::where('role', 'student')->count();
        $educatorCount = User::where('role', 'educator')->count();
        $adminCount = User::where('role', 'admin')->count();
        $courseCount = Course::count();
        $activeCourseCount = Course::where('is_active', true)->count();

        // You could fetch recent activity logs, pending approvals, etc.
        // $recentLogs = ActivityLog::latest()->take(5)->get(); // Assuming ActivityLog model and logging implemented

        // --- Pass Data to the View ---

        // Prepare data array
        $data = [
            'userCount' => $userCount,
            'studentCount' => $studentCount,
            'educatorCount' => $educatorCount,
            'adminCount' => $adminCount,
            'courseCount' => $courseCount,
            'activeCourseCount' => $activeCourseCount,
            // 'recentLogs' => $recentLogs, // Uncomment if fetching logs
        ];

        // Return the view and pass the data
        // Make sure you have a view file at: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', $data);

        // Alternative using the View facade:
        // return View::make('admin.dashboard', $data);
    }

    // You could add other methods here if the Admin Dashboard controller
    // needs to handle more actions specific to the dashboard itself.
}
