<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Course') }}: {{ $course->title }}
            </h2>
             <div>
                 <a href="{{ route('educator.courses.enrollments.index', $course) }}" class="text-sm text-blue-600 hover:underline mr-4">Manage Enrollments</a>
                 <a href="{{ route('educator.courses.contents.create', $course) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Add Content') }}
                </a>
             </div>
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <x-session-status class="mb-4" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4 border-b pb-2">Course Content</h3>
                     @forelse ($course->contents as $content)
                        <div class="mb-4 p-4 border rounded-lg hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-md mb-1">{{ $content->title }}</h4>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $content->is_visible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $content->is_visible ? 'Visible' : 'Hidden' }}</span>
                                    <span class="text-xs ml-2 text-gray-500">({{ ucfirst($content->type) }})</span>
                                    <p class="text-sm text-gray-600 mt-1">{{ $content->description }}</p>
                                    <div class="text-xs text-gray-500 mt-1">
                                         Order: {{ $content->order }} |
                                         Available: {{ $content->available_from?->format('Y-m-d H:i') ?? 'Always' }} |
                                         Due: {{ $content->due_date?->format('Y-m-d H:i') ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-4 space-x-2 whitespace-nowrap">
                                     <a href="{{ route('educator.contents.edit', $content) }}" class="font-medium text-sm text-indigo-600 hover:underline">Edit</a>
                                     <form action="{{ route('educator.contents.destroy', $content) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this content item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-sm text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                             {{-- Maybe show file link or content data preview here --}}
                            @if($content->file_path && $content->type == 'resource')
                                <div class="mt-2 text-xs">
                                    <span class="text-gray-600">File: {{ basename($content->file_path) }}</span>
                                     {{-- Need a secure download route for private files --}}
                                     {{-- <a href="{{ route('secure.download', $content->id) }}" class="ml-2 text-blue-500 hover:underline">[Download]</a> --}}
                                </div>
                             @elseif($content->content_data && $content->type == 'resource')
                                 <div class="mt-2 text-xs text-gray-600">
                                    Content/Link: {{ Str::limit($content->content_data, 50) }}
                                 </div>
                            @endif
                        </div>
                     @empty
                         <p class="text-sm text-center text-gray-500 py-4">No content has been added to this course yet.</p>
                     @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
