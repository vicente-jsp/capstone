<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Use DB facade for aggregations

class AnalyticsController extends Controller
{
    public function show(Course $course)
    {
        // Authorization: Ensure educator owns the course
        if ($course->educator_id !== Auth::id()) {
            abort(403);
        }

        // 1. Get Assignments/Quizzes for this course
        $assignments = CourseContent::where('course_id', $course->id)
            ->whereIn('type', ['assignment', 'quiz']) // Add other gradable types if needed
            ->orderBy('title')
            ->get();

        // 2. Calculate Average Grades per Assignment
        // Use a subquery or join for efficiency on larger datasets
        $averageGrades = DB::table('course_contents')
            ->leftJoin('submissions', 'course_contents.id', '=', 'submissions.course_content_id')
            ->where('course_contents.course_id', $course->id)
            ->whereIn('course_contents.type', ['assignment', 'quiz'])
            ->select(
                'course_contents.id as content_id',
                'course_contents.title as content_title',
                DB::raw('COUNT(submissions.id) as submission_count'), // Count only submitted items
                DB::raw('COUNT(CASE WHEN submissions.grade IS NOT NULL THEN 1 END) as graded_count'), // Count only graded items
                DB::raw('AVG(submissions.grade) as average_grade')
            )
            ->groupBy('course_contents.id', 'course_contents.title')
            ->orderBy('course_contents.title')
            ->get()
            ->keyBy('content_id'); // Key by content ID for easy lookup in the view

        // 3. Calculate Course Completion Rate (Example: Based on submitted assignments)
        $totalAssignments = $assignments->count();
        $enrolledStudentIds = $course->enrolledUsers()->where('role', 'student')->pluck('users.id'); // Get student IDs

        $completionData = [];
         if ($enrolledStudentIds->isNotEmpty() && $totalAssignments > 0) {
             foreach ($enrolledStudentIds as $studentId) {
                 $submittedCount = DB::table('submissions')
                     ->join('course_contents', 'submissions.course_content_id', '=', 'course_contents.id')
                     ->where('course_contents.course_id', $course->id)
                     ->whereIn('course_contents.type', ['assignment', 'quiz'])
                     ->where('submissions.user_id', $studentId)
                     ->distinct('submissions.course_content_id') // Count each assignment only once per student
                     ->count('submissions.course_content_id');

                 $completionRate = ($submittedCount / $totalAssignments) * 100;
                 $student = \App\Models\User::find($studentId); // Fetch student name (can be optimized)
                  if($student) {
                       $completionData[$student->name] = round($completionRate, 2);
                  }
             }
             arsort($completionData); // Sort by completion rate descending
        }


        return view('educator.analytics.show', compact('course', 'averageGrades', 'assignments', 'completionData'));
    }
}
