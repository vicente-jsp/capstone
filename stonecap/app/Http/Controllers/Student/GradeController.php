<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission; // To fetch student's submissions

class GradeController extends Controller
{
    /**
     * Display a listing of the student's grades.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Fetch all submissions made by the logged-in user that have been graded
        $gradedSubmissions = $user->submissions()
                                ->whereNotNull('graded_at') // Only show graded items
                                ->with(['courseContent.course']) // Eager load content and course info
                                ->orderBy('graded_at', 'desc') // Show most recently graded first
                                ->paginate(25);


        // Group by course? Calculate overall course grades? (More complex logic needed)

        return view('student.grades.index', compact('user', 'gradedSubmissions'));
    }

     // Optional: Show grades for a specific course
     // public function showCourseGrades(Course $course) { ... }
}