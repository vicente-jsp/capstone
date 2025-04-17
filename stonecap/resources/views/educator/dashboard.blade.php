<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Educator Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Course Count --}}
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Managed Courses</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $courseCount }}</p>
                         <a href="{{ route('educator.courses.index') }}" class="mt-2 text-sm text-blue-600 hover:underline">View My Courses »</a>
                    </div>
                </div>

                 {{-- Recent Ungraded Submissions --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-3">Recent Ungraded Submissions</h3>
                        @forelse ($recentSubmissions as $submission)
                             <div class="mb-2 pb-2 border-b text-sm">
                                 <a href="{{ route('educator.submissions.grade.edit', $submission) }}" class="font-semibold text-indigo-600 hover:underline">
                                     {{ $submission->student?->name ?? 'Unknown User' }}
                                 </a>
                                 submitted to
                                 <span class="italic">"{{ $submission->courseContent?->title ?? 'Deleted Content' }}"</span>
                                  in course "{{ $submission->courseContent?->course?->title ?? 'Deleted Course' }}"
                                 <span class="text-xs text-gray-500">({{ $submission->submitted_at->diffForHumans() }})</span>
                             </div>
                         @empty
                             <p class="text-sm text-gray-500">No recent ungraded submissions.</p>
                         @endforelse
                         @if($recentSubmissions->count() > 0)
                            <a href="{{ route('educator.courses.submissions.index', ['course' => $recentSubmissions->first()->courseContent->course_id, 'status'=>'pending']) }}" class="mt-3 text-sm text-blue-600 hover:underline">View All Pending Submissions »</a>
                            {{-- Note: Above link assumes the first submission's course, adjust if needed --}}
                         @endif
                    </div>
                </div>

                 {{-- Add more relevant cards - e.g., Announcements, Quick Links --}}

            </div>
        </div>
    </div>
</x-app-layout>