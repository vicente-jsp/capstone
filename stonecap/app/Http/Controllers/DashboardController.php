<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */ // <-- Add this PHPDoc hint
        $user = Auth::user();

        // If the methods ARE in User.php, these lines should now work
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isEducator()) {
            return redirect()->route('educator.dashboard');
        } elseif ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } else {
            Auth::logout();
            return redirect('/login')->with('error', 'Invalid user role.');
        }
    }
}