{{-- Example usage in resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Example Stat Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Total Users</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $userCount }}</p>
                        <p class="text-sm text-gray-600">
                            ({{ $studentCount }} Students, {{ $educatorCount }} Educators, {{ $adminCount }} Admins)
                        </p>
                    </div>
                </div>

                {{-- Example Stat Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Total Courses</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $courseCount }}</p>
                         <p class="text-sm text-gray-600">
                            ({{ $activeCourseCount }} Active)
                        </p>
                    </div>
                </div>

                 {{-- Add more cards/widgets for other stats --}}

            </div>
             {{-- You could add a section for recent activity logs here --}}
             {{-- @if(isset($recentLogs) && $recentLogs->count() > 0) ... @endif --}}
        </div>
    </div>
</x-app-layout>