<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class EnrollmentController extends Controller
{
    /**
     * Display a listing of the enrolled students for a course.
     */
    public function index(Course $course)
    {
        $this->authorizeEducatorOwnsCourse($course); // Authorization

        // Load enrolled students (users with the 'student' role via the pivot table)
        $enrollments = $course->enrollments()->with('user') // Eager load user data
                        // ->whereHas('user', fn($q) => $q->where('role', 'student')) // Optional: Only show students
                         ->paginate(30);

        // Get users who are students AND not already enrolled for the add dropdown/search
        $unenrolledStudents = User::where('role', 'student')
                            ->whereNotIn('id', $course->enrolledUsers()->pluck('users.id'))
                            ->orderBy('name')
                            ->pluck('name', 'id'); // Get as [id => name] for form select

        return view('educator.enrollments.index', compact('course', 'enrollments', 'unenrolledStudents'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorizeEducatorOwnsCourse($course); // Authorization

        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('course_enrollments')->where(function ($query) use ($course) {
                    return $query->where('course_id', $course->id); // Prevent duplicate enrollment
                }),
                // Ensure the selected user is a student (or allowed role)
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'student'); // Only allow enrolling students
                }),
            ],
        ], [
            'user_id.unique' => 'This user is already enrolled in this course.',
            'user_id.exists' => 'The selected user is not a valid student.', // Custom message for role check
        ]);

        $course->enrollments()->create([
            'user_id' => $request->input('user_id'),
            'enrolled_at' => now(),
            // Add role_in_course if using that pivot column
        ]);

        return redirect()->route('educator.courses.enrollments.index', $course)
                         ->with('success', 'User enrolled successfully.');
    }


    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(CourseEnrollment $enrollment) // Route Model Binding for Enrollment
    {
        // Authorization: Check if the logged-in educator owns the course associated with this enrollment
        $this->authorizeEducatorOwnsCourse($enrollment->course);

        $courseId = $enrollment->course_id; // Get course ID before deleting
        $enrollment->delete();

        return redirect()->route('educator.courses.enrollments.index', $courseId)
                         ->with('success', 'User enrollment removed.');
    }

     /**
     * Helper function for authorization.
     */
    private function authorizeEducatorOwnsCourse(Course $course)
    {
        if ($course->educator_id !== Auth::id()) {
            abort(403, 'You do not have permission to manage enrollments for this course.');
        }
    }
}