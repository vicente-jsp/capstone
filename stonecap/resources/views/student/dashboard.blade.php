<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">My Enrolled Courses</h3>
            {{-- <x-session-status class="mb-4" /> --}}

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                 @forelse ($enrolledCourses as $course)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 flex flex-col h-full">
                            <h4 class="text-lg font-semibold mb-2">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600 mb-4 flex-grow">
                                {{ Str::limit($course->description, 120) }}
                            </p>
                             <p class="text-xs text-gray-500 mb-3">
                                Instructor: {{ $course->educator?->name ?? 'N/A' }}
                             </p>
                            <div class="mt-auto pt-4 border-t">
                                <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    View Course
                                </a>
                                 {{-- Add link to course grades? --}}
                            </div>
                        </div>
                    </div>
                 @empty
                     <div class="md:col-span-2 lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                         <div class="p-6 text-gray-900 text-center text-gray-500">
                            You are not enrolled in any active courses.
                         </div>
                     </div>
                 @endforelse
            </div>
             {{-- Add Pagination if needed, though less common for student dashboard course list --}}
             {{-- <div class="mt-6">{{ $enrolledCourses->links() }}</div> --}}
        </div>
    </div>
</x-app-layout>
