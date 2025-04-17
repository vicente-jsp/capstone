<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Assignment') }}: {{ $content->title }}
             <span class="text-base font-normal">(Course: {{ $content->course->title }})</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                 <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-2">Assignment Instructions</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $content->description ?? 'No specific instructions provided.' }}</p>
                    <p class="text-sm font-semibold text-red-600">
                        Due: {{ $content->due_date ? $content->due_date->format('M d, Y H:i') : 'No due date' }}
                         @if($content->due_date && $content->due_date < now()) (Past Due) @endif
                    </p>
                 </div>
             </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Your Submission</h3>
                     {{-- Check if submission is allowed (e.g., not past due date if strict) --}}
                    @if(!$content->due_date || $content->due_date >= now())
                         <form method="POST" action="{{ route('student.contents.submit.store', $content) }}" enctype="multipart/form-data">
                            @csrf
                            @include('student.submissions._form')

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('student.courses.show', $content->course) }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                    {{ __('Cancel') }}
                                </a>
                                <x-primary-button>
                                    {{ __('Submit Assignment') }}
                                </x-primary-button>
                            </div>
                        </form>
                     @else
                        <p class="text-red-600 font-semibold text-center">The due date for this assignment has passed. Submissions are no longer accepted.</p>
                         <div class="mt-4 text-center">
                            <a href="{{ route('student.courses.show', $content->course) }}" class="text-sm text-gray-600 hover:underline">« Back to Course</a>
                         </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>