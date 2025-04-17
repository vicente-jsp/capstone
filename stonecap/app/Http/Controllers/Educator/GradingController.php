<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingController extends Controller
{
    /**
     * Display a listing of submissions for a specific course that need grading or are graded.
     */
    public function index(Course $course, Request $request)
    {
         $this->authorizeEducatorOwnsCourse($course);

         // Base query for submissions within this educator's course
        $submissionsQuery = Submission::whereHas('courseContent.course', function ($q) use ($course) {
                $q->where('id', $course->id);
            })
            ->with(['student', 'courseContent']) // Eager load student and content item
            ->latest('submitted_at'); // Order by submission time

        // --- Filtering ---
        $filter_status = $request->input('status', 'pending'); // Default to pending
        $filter_content_id = $request->input('content_id');

        if ($filter_status === 'pending') {
            $submissionsQuery->whereNull('graded_at');
        } elseif ($filter_status === 'graded') {
            $submissionsQuery->whereNotNull('graded_at');
        } // 'all' status needs no extra where clause

         if ($filter_content_id) {
            $submissionsQuery->where('course_content_id', $filter_content_id);
        }
         // --- End Filtering ---

        $submissions = $submissionsQuery->paginate(25)->withQueryString();

        // Get assignments/quizzes from this course for the filter dropdown
        $gradableContent = $course->contents()
                                ->whereIn('type', ['assignment', 'quiz']) // Add other gradable types
                                ->orderBy('title')
                                ->pluck('title', 'id');

        return view('educator.grading.index', compact('course', 'submissions', 'gradableContent', 'filter_status', 'filter_content_id'));
    }

    /**
     * Show the form for editing (grading) the specified submission.
     */
    public function edit(Submission $submission)
    {
        // Authorization: Ensure educator owns the course this submission belongs to
        $submission->load('courseContent.course', 'student', 'grader'); // Eager load necessary data
        $this->authorizeEducatorOwnsCourse($submission->courseContent->course);

        // Check if already graded by someone else? Optional.

        return view('educator.grading.edit', compact('submission'));
    }

    /**
     * Update the specified submission in storage (Save Grade and Feedback).
     */
    public function update(Request $request, Submission $submission)
    {
         // Authorization
         $submission->load('courseContent.course');
         $this->authorizeEducatorOwnsCourse($submission->courseContent->course);

        $request->validate([
            'grade' => 'nullable|numeric|min:0', // Adjust max based on assignment scale if needed
            'feedback' => 'nullable|string|max:5000',
        ]);

        // Prevent re-grading if already graded? Or allow overwriting? Allow for now.

        $submission->update([
            'grade' => $request->input('grade'),
            'feedback' => $request->input('feedback'),
            'graded_by' => Auth::id(), // Record who graded it
            'graded_at' => now(),     // Mark as graded
        ]);

        // Maybe notify student?

        // Redirect back to the grading index for the course, maybe with filters preserved
         return redirect()->route('educator.courses.submissions.index', $submission->courseContent->course_id)
                         ->with('success', 'Submission graded successfully.');
    }

     /**
     * Helper function for authorization.
     */
    private function authorizeEducatorOwnsCourse(Course $course)
    {
        if ($course->educator_id !== Auth::id()) {
            abort(403, 'You do not have permission to grade submissions for this course.');
        }
    }
}