<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grade Submissions for Course') }}: {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <x-session-status class="mb-4" />

             {{-- Filter Form --}}
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('educator.courses.submissions.index', $course) }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="content_id" :value="__('Assignment/Quiz')" />
                                <select name="content_id" id="content_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="">-- All Content --</option>
                                    @foreach($gradableContent as $id => $title)
                                        <option value="{{ $id }}" @selected($filter_content_id == $id)>{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="pending" @selected($filter_status == 'pending')>Pending Grading</option>
                                    <option value="graded" @selected($filter_status == 'graded')>Already Graded</option>
                                    <option value="all" @selected($filter_status == 'all')>All Submissions</option>
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <x-primary-button class="h-10">Filter</x-primary-button>
                                <a href="{{ route('educator.courses.submissions.index', $course) }}" class="h-10 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150">Reset</a>
                           </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Submissions Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-3">Submissions</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                             <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Student</th>
                                    <th scope="col" class="px-6 py-3">Content Item</th>
                                    <th scope="col" class="px-6 py-3">Submitted At</th>
                                    <th scope="col" class="px-6 py-3">Grade</th>
                                    <th scope="col" class="px-6 py-3">Graded At</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                             <tbody>
                                @forelse ($submissions as $submission)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $submission->student?->name ?? 'User Deleted' }}
                                    </th>
                                    <td class="px-6 py-4">{{ $submission->courseContent?->title ?? 'Content Deleted' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $submission->submitted_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if($submission->graded_at)
                                            <span class="font-semibold">{{ $submission->grade ?? 'N/G' }}</span>
                                        @else
                                            <span class="text-xs text-gray-500 italic">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $submission->graded_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('educator.submissions.grade.edit', $submission) }}" class="font-medium text-indigo-600 hover:underline">
                                            {{ $submission->graded_at ? 'View/Edit Grade' : 'Grade Now' }}
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No submissions found matching your criteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>
            <div class="mt-6 text-center">
                 <a href="{{ route('educator.courses.show', $course) }}" class="text-sm text-gray-600 hover:underline">« Back to Course Management</a>
             </div>
        </div>
    </div>
</x-app-layout>