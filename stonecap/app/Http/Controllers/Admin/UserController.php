<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Base Controller
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
         return view('admin.users.create');
    }

    public function store(Request $request)
    {
         $request->validate([
             'name' => ['required', 'string', 'max:255'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
             'password' => ['required', 'confirmed', Rules\Password::defaults()],
             'role' => ['required', 'in:admin,educator,student'],
         ]);

         User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'role' => $request->role,
         ]);

         // Optional: Log activity
         // ActivityLog::create([...]);

         return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

     public function show(User $user)
     {
         // Add view for showing user details, activity logs, enrolled courses etc.
         $user->load('enrolledCourses', 'activityLogs'); // Eager load
         return view('admin.users.show', compact('user'));
     }


    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
         $request->validate([
             'name' => ['required', 'string', 'max:255'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
             'role' => ['required', 'in:admin,educator,student'],
             'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Optional password update
         ]);

         $data = $request->only('name', 'email', 'role');
         if ($request->filled('password')) {
             $data['password'] = Hash::make($request->password);
         }

         $user->update($data);

         return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
         if ($user->id === Auth::id()) {
              return back()->with('error', 'You cannot delete your own account.');
         }
         // Add checks: maybe prevent deleting users with active courses/submissions?
         $user->delete();
         return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}