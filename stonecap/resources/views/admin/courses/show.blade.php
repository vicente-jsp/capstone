<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Course Details') }}: {{ $course->title }}
            </h2>
             <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Edit Course') }}
            </a>
         </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Course Info Card --}}
            <div class="md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-4">Course Information</h3>
                     <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->title }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $course->description ?? 'N/A' }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Educator</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->educator?->name ?? 'N/A' }}</dd>
                        </div>
                         <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                 <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                          <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $course->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                     </dl>
                </div>
            </div>

            {{-- Enrolled Users --}}
             <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-4">Enrolled Users ({{ $course->enrolledUsers->count() }})</h3>
                      <ul class="space-y-2 max-h-60 overflow-y-auto">
                         @forelse ($course->enrolledUsers as $student)
                             <li class="text-sm text-gray-700">
                                 <a href="{{ route('admin.users.show', $student) }}" class="hover:underline">{{ $student->name }}</a> ({{ $student->email }})
                                  <span class="text-xs text-gray-500 ml-2">(Enrolled: {{ $student->pivot?->enrolled_at?->format('Y-m-d') ?? 'N/A' }})</span>
                             </li>
                         @empty
                             <li class="text-sm text-gray-500">No users are currently enrolled in this course.</li>
                         @endforelse
                      </ul>
                      {{-- Link to manage enrollments (maybe educator only?) --}}
                 </div>
             </div>

            {{-- Course Content --}}
             <div class="md:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-4">Course Content</h3>
                     {{-- Add link to create content (maybe educator only?) --}}
                     @forelse ($course->contents as $content)
                          <div class="mb-3 p-3 border rounded-lg">
                             <h4 class="font-semibold text-md">{{ $content->title }}</h4>
                             <p class="text-sm text-gray-600">{{ $content->description }}</p>
                             <div class="text-xs text-gray-500 mt-1">
                                 Type: {{ ucfirst($content->type) }} |
                                 Visible: {{ $content->is_visible ? 'Yes' : 'No' }} |
                                 Order: {{ $content->order }} |
                                 Due: {{ $content->due_date?->format('Y-m-d H:i') ?? 'N/A' }}
                             </div>
                             {{-- Add edit/delete links for content (maybe educator only?) --}}
                         </div>
                     @empty
                         <p class="text-sm text-gray-500">No content has been added to this course yet.</p>
                     @endforelse
                 </div>
             </div>

        </div>
    </div>
</x-app-layout>