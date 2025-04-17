<x-app-layout>
    <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Courses') }}
        </h2>
        {{-- Maybe add 'Create Course' button if educators can create them --}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <x-session-status class="mb-4" />
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                 @forelse ($courses as $course)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 flex flex-col h-full"> {{-- Flex for button at bottom --}}
                            <h3 class="text-lg font-semibold mb-2">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4 flex-grow"> {{-- Flex grow pushes button down --}}
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div class="mt-auto pt-4 border-t"> {{-- Button area --}}
                                <a href="{{ route('educator.courses.show', $course) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Manage Course
                                </a>
                                 {{-- Add other links like View Analytics --}}
                                <a href="{{ route('educator.courses.analytics.show', $course) }}" class="ml-2 text-sm text-blue-600 hover:underline">Analytics</a>
                            </div>
                        </div>
                    </div>
                 @empty
                    <div class="md:col-span-2 lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                         <div class="p-6 text-gray-900 text-center text-gray-500">
                             You are not managing any courses yet.
                             {{-- Link to create course or contact admin --}}
                         </div>
                     </div>
                 @endforelse
            </div>
             <div class="mt-6">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>