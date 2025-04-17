<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User; // Needed to list educators
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Optional: For Policies

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gate::authorize('viewAny', Course::class); // Optional Policy check
        $courses = Course::with('educator')->latest()->paginate(20); // Eager load educator
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('create', Course::class); // Optional Policy check
        $educators = User::where('role', 'educator')->orderBy('name')->pluck('name', 'id');
        return view('admin.courses.create', compact('educators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Gate::authorize('create', Course::class); // Optional Policy check
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'educator_id' => 'nullable|exists:users,id', // Validate educator exists
            'is_active' => 'boolean',
            // Add other fields like theme_color if needed
        ]);

        Course::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'educator_id' => $request->input('educator_id'),
            'is_active' => $request->input('is_active', true), // Default to true if not sent
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Gate::authorize('view', $course); // Optional Policy check
        // Load relationships needed for the detail view
        $course->load('educator', 'contents', 'enrolledUsers');
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // Gate::authorize('update', $course); // Optional Policy check
        $educators = User::where('role', 'educator')->orderBy('name')->pluck('name', 'id');
        return view('admin.courses.edit', compact('course', 'educators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        // Gate::authorize('update', $course); // Optional Policy check
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'educator_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $course->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'educator_id' => $request->input('educator_id'),
            'is_active' => $request->input('is_active', false), // If checkbox unchecked, value is false
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        // Gate::authorize('delete', $course); // Optional Policy check

        // Add checks? Prevent deletion if students enrolled/submitted work?
        // $enrollmentCount = $course->enrollments()->count();
        // if ($enrollmentCount > 0) {
        //     return back()->with('error', 'Cannot delete course with enrolled students.');
        // }

        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}