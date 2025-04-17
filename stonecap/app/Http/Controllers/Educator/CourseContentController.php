<?php

namespace App\Http\Controllers\Educator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // For file uploads/deletes
use Illuminate\Support\Facades\Gate; // Optional

class CourseContentController extends Controller
{
    // Note: Using shallow nesting, so index/create are relative to course,
    // but show/edit/update/destroy use the content ID directly.

    /**
     * Display a listing of the resource for a specific course.
     * (Often handled within the CourseController@show view)
     */
    // public function index(Course $course) { ... }

    /**
     * Show the form for creating a new resource in a course.
     */
    public function create(Course $course)
    {
        $this->authorizeEducatorOwnsCourse($course); // Authorization
        return view('educator.contents.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorizeEducatorOwnsCourse($course); // Authorization

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:resource,assignment,quiz,forum_topic,announcement',
            'content_data' => 'nullable|string', // Adjust validation based on type later
            'resource_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,png,txt|max:20480', // 20MB Example
            'order' => 'nullable|integer',
            'is_visible' => 'boolean',
            'available_from' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:available_from',
        ]);

        $filePath = null;
        if ($request->hasFile('resource_file') && $request->input('type') === 'resource') {
             // Store file relative to the course maybe? e.g., courses/{course_id}/content/{filename}
            $filePath = $request->file('resource_file')->store("courses/{$course->id}/content", 'private'); // Use a private disk
        }

        CourseContent::create([
            'course_id' => $course->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'content_data' => $request->input('content_data'), // Store URL, text, quiz settings etc.
            'file_path' => $filePath,
            'order' => $request->input('order', 0),
            'is_visible' => $request->input('is_visible', false),
            'available_from' => $request->input('available_from'),
            'due_date' => $request->input('due_date'),
        ]);

        return redirect()->route('educator.courses.show', $course)->with('success', 'Course content added.');
    }

    /**
     * Display the specified resource. (Often not needed, viewed within course)
     */
    // public function show(CourseContent $content) { ... }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseContent $content)
    {
        // Load the course relationship to pass to the view
        $content->load('course');
        $this->authorizeEducatorOwnsCourse($content->course); // Authorization
        return view('educator.contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseContent $content)
    {
        $this->authorizeEducatorOwnsCourse($content->course); // Authorization

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:resource,assignment,quiz,forum_topic,announcement',
            'content_data' => 'nullable|string',
            'resource_file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,png,txt|max:20480',
            'order' => 'nullable|integer',
            'is_visible' => 'boolean',
            'available_from' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:available_from',
            'remove_file' => 'nullable|boolean', // Checkbox to remove existing file
        ]);

        $filePath = $content->file_path; // Keep existing file path by default

        // Handle file removal
        if ($request->input('remove_file') && $filePath) {
            Storage::disk('private')->delete($filePath);
            $filePath = null;
        }

        // Handle new file upload (replaces old if exists and remove_file wasn't checked)
        if ($request->hasFile('resource_file') && $request->input('type') === 'resource') {
            // Delete old file first if replacing
            if ($content->file_path) {
                 Storage::disk('private')->delete($content->file_path);
            }
            $filePath = $request->file('resource_file')->store("courses/{$content->course_id}/content", 'private');
        }

        $content->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'content_data' => $request->input('content_data'),
            'file_path' => $filePath,
            'order' => $request->input('order', 0),
            'is_visible' => $request->input('is_visible', false),
            'available_from' => $request->input('available_from'),
            'due_date' => $request->input('due_date'),
        ]);

        return redirect()->route('educator.courses.show', $content->course_id)->with('success', 'Course content updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseContent $content)
    {
         $this->authorizeEducatorOwnsCourse($content->course); // Authorization
         $courseId = $content->course_id; // Get course ID before deleting content

         // Delete associated file if it exists
        if ($content->file_path) {
            Storage::disk('private')->delete($content->file_path);
        }

        // Delete submissions associated with this content? Add logic if needed.

        $content->delete();

        return redirect()->route('educator.courses.show', $courseId)->with('success', 'Course content deleted.');
    }

    /**
     * Helper function for authorization.
     */
    private function authorizeEducatorOwnsCourse(Course $course)
    {
        if ($course->educator_id !== Auth::id()) {
            abort(403, 'You do not have permission to manage this course content.');
            // Or use Laravel Gates/Policies: Gate::authorize('update', $course);
        }
    }
}