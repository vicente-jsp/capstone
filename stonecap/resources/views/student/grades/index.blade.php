<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Grades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <x-session-status class="mb-4" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-3">Graded Items</h3>
                     <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                             <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Course</th>
                                    <th scope="col" class="px-6 py-3">Item</th>
                                    <th scope="col" class="px-6 py-3">Grade</th>
                                    <th scope="col" class="px-6 py-3">Feedback</th>
                                    <th scope="col" class="px-6 py-3">Graded On</th>
                                </tr>
                            </thead>
                             <tbody>
                                @forelse ($gradedSubmissions as $submission)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('student.courses.show', $submission->courseContent?->course_id) }}" class="hover:underline">
                                            {{ $submission->courseContent?->course?->title ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $submission->courseContent?->title ?? 'Content Deleted' }}
                                    </th>
                                    <td class="px-6 py-4 font-semibold">
                                        {{ $submission->grade ?? 'N/G' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                         <div class="max-w-xs whitespace-pre-wrap">{{ $submission->feedback ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $submission->graded_at?->format('Y-m-d H:i') ?? '-' }}</td>

                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        You do not have any graded submissions yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $gradedSubmissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>