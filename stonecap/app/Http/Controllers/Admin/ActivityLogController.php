<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog; // Assuming a simple ActivityLog model
// Or use Spatie Activity Log package: use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity log.
     */
    public function index(Request $request)
    {
        // Basic implementation - Consider Spatie package for advanced features
        $logsQuery = ActivityLog::with('user')->latest(); // Eager load user

        // --- Filtering Examples (Optional) ---
        if ($request->filled('user_id')) {
            $logsQuery->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('action')) {
             $logsQuery->where('action', 'like', '%' . $request->input('action') . '%');
        }
        if ($request->filled('date_from')) {
             $logsQuery->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
             $logsQuery->whereDate('created_at', '<=', $request->input('date_to'));
        }
        // --- End Filtering ---


        $logs = $logsQuery->paginate(50)->withQueryString(); // Keep filters in pagination links

        // You might need users list for filter dropdown
        $users = \App\Models\User::orderBy('name')->pluck('name', 'id');

        return view('admin.activity_logs.index', compact('logs', 'users'));
    }

    // Show details for a specific log entry (Optional)
    // public function show(ActivityLog $activityLog) { ... }

     // Destroy log entries (Optional - use with caution!)
     // public function destroy(ActivityLog $activityLog) { ... }
     // public function bulkDestroy(Request $request) { ... } // Delete multiple logs
}