<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grade Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Submission Details --}}
                     <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium mb-2">Submission Details</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                             <div><dt class="text-gray-500">Student:</dt> <dd class="font-semibold">{{ $submission->student?->name ?? 'User Deleted' }}</dd></div>
                            <div><dt class="text-gray-500">Course:</dt> <dd>{{ $submission->courseContent?->course?->title ?? 'Course Deleted' }}</dd></div>
                            <div><dt class="text-gray-500">Item:</dt> <dd>{{ $submission->courseContent?->title ?? 'Content Deleted' }}</dd></div>
                            <div><dt class="text-gray-500">Submitted:</dt> <dd>{{ $submission->submitted_at->format('M d, Y H:i') }}</dd></div>
                        </dl>

                         {{-- Display Submitted Content --}}
                        <div class="mt-4">
                            <h4 class="text-base font-medium mb-1">Student's Submission:</h4>
                             @if($submission->content)
                                <div class="prose max-w-none p-3 border rounded bg-gray-50 text-sm">
                                    {!! nl2br(e($submission->content)) !!}
                                </div>
                             @endif
                             @if($submission->file_path)
                                 <div class="mt-2 text-sm">
                                    <span class="font-semibold">Submitted File:</span>
                                    {{ basename($submission->file_path) }}
                                    {{-- Need Secure Download Route --}}
                                    {{-- <a href="{{ route('secure.download.submission', $submission->id) }}" target="_blank" class="ml-2 text-blue-600 hover:underline">[Download File]</a> --}}
                                     <span class="ml-2 text-red-600">(Download Link Not Implemented)</span>
                                </div>
                             @endif
                             @if(!$submission->content && !$submission->file_path)
                                <p class="text-sm italic text-gray-500">No text or file submitted.</p>
                             @endif
                        </div>
                    </div>

                    {{-- Grading Form --}}
                    <form method="POST" action="{{ route('educator.submissions.grade.update', $submission) }}">
                        @csrf
                        @method('PATCH')

                        <h3 class="text-lg font-medium mb-3">Enter Grade & Feedback</h3>

                         <!-- Grade -->
                        <div>
                            <x-input-label for="grade" :value="__('Grade (Numeric)')" />
                            <x-text-input id="grade" class="block mt-1 w-full md:w-1/2" type="number" step="0.01" name="grade" :value="old('grade', $submission->grade)" />
                            <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                        </div>

                        <!-- Feedback -->
                        <div class="mt-4">
                            <x-input-label for="feedback" :value="__('Feedback')" />
                             <textarea id="feedback" name="feedback" rows="6"
                                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                      >{{ old('feedback', $submission->feedback) }}</textarea>
                            <x-input-error :messages="$errors->get('feedback')" class="mt-2" />
                        </div>

                         <div class="mt-4 text-xs text-gray-500">
                             @if($submission->graded_at)
                                Last graded by {{ $submission->grader?->name ?? 'Unknown' }} on {{ $submission->graded_at->format('Y-m-d H:i') }}. Saving will overwrite.
                             @else
                                This submission has not been graded yet.
                             @endif
                         </div>

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('educator.courses.submissions.index', $submission->courseContent->course_id) }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Save Grade & Feedback') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>