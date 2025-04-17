<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- Add this for the Auth facade

// --- Base/Auth Controllers ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleChoiceController; // For role choice page

// --- Admin Controllers ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController; // Use alias if needed
use App\Http\Controllers\Admin\CourseController as AdminCourseController; // Import & Alias
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController; // Import & Alias
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController; // Import & Alias

// --- Educator Controllers ---
use App\Http\Controllers\Educator\EducatorDashboardController; // Import
use App\Http\Controllers\Educator\CourseController as EducatorCourseController; // Import & Alias
use App\Http\Controllers\Educator\CourseContentController as EducatorCourseContentController; // Import & Alias
use App\Http\Controllers\Educator\EnrollmentController as EducatorEnrollmentController; // Import & Alias
use App\Http\Controllers\Educator\GradingController as EducatorGradingController; // Import & Alias
use App\Http\Controllers\Educator\AnalyticsController as EducatorAnalyticsController; // Import & Alias

// --- Student Controllers ---
use App\Http\Controllers\Student\StudentDashboardController; // Import
use App\Http\Controllers\Student\CourseController as StudentCourseController; // Import & Alias
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController; // Import & Alias
use App\Http\Controllers\Student\GradeController as StudentGradeController; // Import
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Welcome Page ---
Route::get('/', function () {
    // If user is logged in but somehow hits /, maybe send to role choice? Optional.
    // if (Auth::check()) {
    //     return redirect()->route('choose-role.show');
    // }
    return view('welcome');
})->name('welcome'); // Give it a name


// --- Role Choice Routes (Protected by Auth) ---
Route::middleware(['auth', 'verified'])->group(function () { // 'verified' ensures email is verified if enabled
    Route::get('/choose-role', [RoleChoiceController::class, 'show'])->name('choose-role.show');
    Route::post('/confirm-role', [RoleChoiceController::class, 'confirm'])->name('choose-role.confirm');

    // Profile routes remain protected
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- Role Specific Dashboard/Area Routes (Protected by Auth and Role Middleware) ---
Route::middleware('auth')->group(function () { // Base auth check

    // --- Admin Routes ---
    Route::middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])
      ->prefix('admin')
      ->name('admin.')
      ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('courses', AdminCourseController::class);
        Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        // Add other admin-specific routes
    });

    // --- Educator Routes ---
    Route::middleware([\App\Http\Middleware\EnsureUserIsEducator::class])
        ->prefix('educator')
        ->name('educator.')
        ->group(function () {
        Route::get('/dashboard', [EducatorDashboardController::class, 'index'])->name('dashboard');
        Route::get('courses', [EducatorCourseController::class, 'index'])->name('courses.index');
        Route::get('courses/{course}', [EducatorCourseController::class, 'show'])->name('courses.show');
        Route::resource('courses.contents', EducatorCourseContentController::class)->shallow();
        Route::get('courses/{course}/enrollments', [EducatorEnrollmentController::class, 'index'])->name('courses.enrollments.index');
        Route::post('courses/{course}/enrollments', [EducatorEnrollmentController::class, 'store'])->name('courses.enrollments.store');
        Route::delete('enrollments/{enrollment}', [EducatorEnrollmentController::class, 'destroy'])->name('enrollments.destroy');
        Route::get('courses/{course}/submissions', [EducatorGradingController::class, 'index'])->name('courses.submissions.index');
        Route::get('submissions/{submission}/grade', [EducatorGradingController::class, 'edit'])->name('submissions.grade.edit');
        Route::patch('submissions/{submission}/grade', [EducatorGradingController::class, 'update'])->name('submissions.grade.update');
        Route::get('courses/{course}/analytics', [EducatorAnalyticsController::class, 'show'])->name('courses.analytics.show');
    });

    // --- Student Routes ---
    Route::middleware([\App\Http\Middleware\EnsureUserIsStudent::class])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
        Route::get('contents/{content}/submit', [StudentSubmissionController::class, 'create'])->name('contents.submit.create');
        Route::post('contents/{content}/submit', [StudentSubmissionController::class, 'store'])->name('contents.submit.store');
        Route::get('grades', [StudentGradeController::class, 'index'])->name('grades.index');
    });

}); // End base auth group

// --- Breeze Authentication Routes ---
// This line should remain at the end to include login, register, password reset, etc.
require __DIR__.'/auth.php';