<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleChoiceController extends Controller
{
    /**
     * Show the form for choosing the user role.
     */
    public function show(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        
       return view('auth.choose-role', ['user' => $user]);
    }

    /**
     * Confirm the chosen role and redirect to the appropriate dashboard.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'chosen_role' => ['required', 'string', 'in:admin,educator,student'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $chosenRole = $request->input('chosen_role');

        // Security Check: Verify the user actually has the role they chose
        $canProceed = false;
        $redirectRoute = route('choose-role.show'); // Default redirect back if role invalid

        if ($chosenRole === 'admin' && $user->isAdmin()) {
            $canProceed = true;
            $redirectRoute = route('admin.dashboard');
        } elseif ($chosenRole === 'educator' && $user->isEducator()) {
            $canProceed = true;
            $redirectRoute = route('educator.dashboard');
        } elseif ($chosenRole === 'student' && $user->isStudent()) {
            $canProceed = true;
            $redirectRoute = route('student.dashboard');
        } else {
            // If the user doesn't have the chosen role, maybe redirect them
            // to their *actual* highest priority dashboard instead of just back?
            // Or just redirect back with an error. Let's redirect back for now.
             return redirect()->route('choose-role.show')
                 ->with('error', 'You do not have permission to access the selected role.');
        }

        if ($canProceed) {
            // Optional: Store the confirmed role in the session if needed later?
            // session(['confirmed_role' => $chosenRole]);
            return redirect()->intended($redirectRoute); // Use intended() in case they were trying to reach a specific page
        } else {
             // This part might be redundant now due to the else block above, but belt-and-suspenders
             return redirect()->route('choose-role.show')
                 ->with('error', 'Invalid role selection.');
        }
    }
}
