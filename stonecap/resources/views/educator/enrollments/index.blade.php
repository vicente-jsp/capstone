<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Enrollments for Course') }}: {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <x-session-status class="mb-4" />

            {{-- Enroll New Student Form --}}
             <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-3">Enroll New Student</h3>
                     <form method="POST" action="{{ route('educator.courses.enrollments.store', $course) }}">
                        @csrf
                        <div class="flex items-start space-x-3">
                            <div class="flex-grow">
                                <x-input-label for="user_id" :value="__('Select Student')" class="sr-only" />
                                 <select name="user_id" id="user_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                    <option value="">-- Select a student to enroll --</option>
                                    @foreach($unenrolledStudents as $id => $name)
                                        <option value="{{ $id }}">{{ $name }} (ID: {{ $id }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                                @if($unenrolledStudents->isEmpty())
                                    <p class="mt-2 text-sm text-gray-500">All students are already enrolled or no students exist.</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0 pt-1"> {{-- Align button slightly --}}
                                <x-primary-button type="submit" :disabled="$unenrolledStudents->isEmpty()">
                                    {{ __('Enroll Student') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Enrolled Students List --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-3">Currently Enrolled Users ({{ $enrollments->total() }})</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Student Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Enrolled On</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($enrollments as $enrollment)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $enrollment->user?->name ?? 'User Deleted' }}
                                    </th>
                                    <td class="px-6 py-4">{{ $enrollment->user?->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $enrollment->enrolled_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        @if($enrollment->user) {{-- Only show if user exists --}}
                                        <form action="{{ route('educator.enrollments.destroy', $enrollment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to unenroll this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline text-xs">Unenroll</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        No users are currently enrolled.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
             <div class="mt-6 text-center">
                 <a href="{{ route('educator.courses.show', $course) }}" class="text-sm text-gray-600 hover:underline">« Back to Course Management</a>
             </div>
        </div>
    </div>
</x-app-layout>