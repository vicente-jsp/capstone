<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- User Info Card --}}
            <div class="md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">User Information</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Joined</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'No' }}</dd>
                        </div>
                    </dl>
                     <div class="mt-6 flex space-x-3">
                         <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>
                         <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                             <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    {{-- Prevent deleting self --}}
                                    @if($user->id === Auth::id()) disabled title="You cannot delete your own account." @endif >
                                Delete
                            </button>
                        </form>
                     </div>
                </div>
            </div>

             {{-- Enrolled Courses --}}
             <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-4">Enrolled Courses</h3>
                      @forelse ($user->enrolledCourses as $course)
                         <div class="mb-2 pb-2 border-b">
                             <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 hover:underline">{{ $course->title }}</a>
                             <span class="text-sm text-gray-500 ml-2">(Enrolled: {{ $course->pivot?->enrolled_at?->format('Y-m-d') ?? 'N/A' }})</span>
                         </div>
                     @empty
                         <p class="text-sm text-gray-500">This user is not enrolled in any courses.</p>
                     @endforelse
                 </div>
             </div>

             {{-- Activity Log (Optional) --}}
             @if($user->activityLogs)
                 <div class="md:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6 text-gray-900">
                         <h3 class="text-lg font-medium mb-4">Recent Activity (Last 10)</h3>
                          <ul class="space-y-2">
                             @forelse ($user->activityLogs->take(10) as $log)
                                 <li class="text-sm text-gray-700">
                                     <span class="font-medium">{{ $log->action }}</span>
                                     @if($log->loggable)
                                         - {{ $log->loggable_type }}:{{ $log->loggable_id }}
                                     @endif
                                     <span class="text-xs text-gray-500 ml-2">({{ $log->created_at->diffForHumans() }} from {{ $log->ip_address ?? 'N/A' }})</span>
                                     @if($log->details)
                                        <pre class="mt-1 text-xs bg-gray-100 p-1 rounded overflow-x-auto">{{ json_encode($log->details, JSON_PRETTY_PRINT) }}</pre>
                                     @endif
                                 </li>
                             @empty
                                 <li class="text-sm text-gray-500">No recent activity recorded for this user.</li>
                             @endforelse
                          </ul>
                         <div class="mt-4">
                            <a href="{{ route('admin.activity-logs.index', ['user_id' => $user->id]) }}" class="text-sm text-blue-600 hover:underline">View All Activity for this User »</a>
                         </div>
                     </div>
                 </div>
             @endif

        </div>
    </div>
</x-app-layout>