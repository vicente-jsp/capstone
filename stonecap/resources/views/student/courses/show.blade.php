<x-app-layout>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ $course->title }}
         </h2>
     </x-slot>

     <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                 <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-2">Course Description</h3>
                     <p class="text-gray-600">{{ $course->description ?? 'No description provided.' }}</p>
                 </div>
             </div>

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-4">Course Content</h3>

                     @if(session('success'))
                         <div class="mb-4 font-medium text-sm text-green-600"> {{ session('success') }} </div>
                     @endif
                      @if(session('error'))
                         <div class="mb-4 font-medium text-sm text-red-600"> {{ session('error') }} </div>
                     @endif
                       @if(session('warning'))
                         <div class="mb-4 font-medium text-sm text-yellow-600"> {{ session('warning') }} </div>
                     @endif


                     @forelse ($course->contents as $content)
                         <div class="mb-4 p-4 border rounded-lg">
                             <h4 class="font-semibold text-md mb-1">{{ $content->title }} ({{ ucfirst($content->type) }})</h4>
                             <p class="text-sm text-gray-600 mb-2">{{ $content->description }}</p>

                             {{-- Display content based on type --}}
                             @if ($content->type == 'resource')
                                 @if ($content->file_path)
                                     {{-- Need a route to download private files securely --}}
                                     <a href="{{ route('download.resource', $content->id) }}" class="text-blue-600 hover:underline text-sm">Download Resource</a>
                                 @elseif (filter_var($content->content_data, FILTER_VALIDATE_URL))
                                     <a href="{{ $content->content_data }}" target="_blank" class="text-blue-600 hover:underline text-sm">View Resource Link</a>
                                 @else
                                     <div class="text-sm prose max-w-none">{!! nl2br(e($content->content_data)) !!}</div>
                                     {{-- Use Markdown parser if storing markdown --}}
                                 @endif
                             @elseif ($content->type == 'assignment')
                                 <div class="text-sm mb-2">
                                     @if($content->due_date)
                                         Due: {{ $content->due_date->format('M d, Y H:i') }}
                                         @if($content->due_date < now()) (Past Due) @endif
                                     @else
                                         No due date.
                                     @endif
                                 </div>
                                 {{-- Check submission status (Requires more logic/data passed from controller) --}}
                                 {{-- @if ($submission_status[$content->id] == 'submitted') ... @endif --}}
                                 @if(!$content->due_date || $content->due_date >= now()) {{-- Basic check --}}
                                    <a href="{{ route('student.contents.submit.create', $content->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 text-sm">
                                         Submit Assignment
                                     </a>
                                 @endif
                                  {{-- Add link to view submission/grade if submitted/graded --}}

                             @elseif ($content->type == 'quiz')
                                 {{-- Quiz taking logic needed here --}}
                                 <span class="text-sm text-gray-500">(Quiz functionality not implemented)</span>
                                 {{-- <a href="..." class="text-blue-600 hover:underline text-sm">Take Quiz</a> --}}

                             @elseif ($content->type == 'forum_topic')
                                  {{-- Forum viewing/posting logic needed here --}}
                                  <span class="text-sm text-gray-500">(Forum functionality not implemented)</span>
                                 {{-- <a href="..." class="text-blue-600 hover:underline text-sm">View Forum</a> --}}

                             @elseif ($content->type == 'announcement')
                                  <div class="text-sm prose max-w-none p-3 bg-yellow-50 rounded">
                                    {!! nl2br(e($content->content_data)) !!}
                                  </div>
                             @endif
                         </div>
                     @empty
                         <p class="text-gray-500">No content has been added to this course yet.</p>
                     @endforelse
                 </div>
             </div>
         </div>
     </div>
     {{-- Need a route/controller method for downloading files --}}
     {{-- Route::get('/download/resource/{content}', [FileDownloadController::class, 'downloadResource'])->name('download.resource')->middleware('auth'); --}}
 </x-app-layout>