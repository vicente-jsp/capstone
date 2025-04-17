<?php 

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseContent;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // For file uploads

class SubmissionController extends Controller
{
    public function create(CourseContent $content)
    {
        // Authorization: Check if content type allows submission & student is enrolled in the course
         if (!in_array($content->type, ['assignment', 'quiz'])) { // Extend quiz logic later
             abort(404, 'Cannot submit to this content type.');
         }
        /** @var \App\Models\User $user */ // <-- Add this PHPDoc hint
        $user = Auth::user();
        if (!$user->enrolledCourses()->where('course_id', $content->course_id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Check if already submitted (prevent resubmission if needed)
        $existingSubmission = Submission::where('course_content_id', $content->id)
                                        ->where('user_id', $user->id)
                                        ->first();
         if ($existingSubmission) {
             // Decide: Allow editing? Show message?
             // return redirect()->route('student.courses.show', $content->course_id)
             //     ->with('warning', 'You have already submitted this assignment.');
         }


        // Check due date
        if ($content->due_date && $content->due_date < now()) {
             return redirect()->route('student.courses.show', $content->course_id)
                 ->with('error', 'The due date for this assignment has passed.');
        }


        return view('student.submissions.create', compact('content'));
    }

    public function store(Request $request, CourseContent $content)
    {
         // Authorization (repeat checks or use middleware/policies)
         if (!in_array($content->type, ['assignment', 'quiz'])) {
             abort(404);
         }
         /** @var \App\Models\User $user */ // <-- Add this PHPDoc hint
         $user = Auth::user();
          if (!$user->enrolledCourses()->where('course_id', $content->course_id)->exists()) {
             abort(403);
         }
          if ($content->due_date && $content->due_date < now()) {
              return back()->with('error', 'The due date has passed.');
         }
        // Prevent duplicate submissions
         $existingSubmission = Submission::where('course_content_id', $content->id)
                                         ->where('user_id', $user->id)
                                         ->first();
         if ($existingSubmission) {
             return back()->with('error', 'You have already submitted.');
         }


        $request->validate([
            'submission_text' => 'nullable|string|max:5000', // Example validation
            'submission_file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip|max:10240', // 10MB max
        ]);

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            // Store the file (e.g., in storage/app/submissions/{user_id}/{content_id}/file.pdf)
            // Ensure 'submissions' disk is configured in config/filesystems.php
            $filePath = $request->file('submission_file')->store("submissions/{$user->id}/{$content->id}", 'private'); // Use a private disk
        }

        Submission::create([
            'course_content_id' => $content->id,
            'user_id' => $user->id,
            'content' => $request->input('submission_text'),
            'file_path' => $filePath,
            'submitted_at' => now(),
        ]);

        // Log activity, notify educator?

        return redirect()->route('student.courses.show', $content->course_id)
                         ->with('success', 'Assignment submitted successfully!');
    }
}