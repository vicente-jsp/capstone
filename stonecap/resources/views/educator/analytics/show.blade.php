<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Analytics for: {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Average Grades Per Assignment --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Average Grades per Assignment</h3>
                    @if($averageGrades->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach($assignments as $assignment)
                                @php $stats = $averageGrades->get($assignment->id); @endphp
                                <li class="text-sm border-b pb-2">
                                    <strong>{{ $assignment->title }}</strong>:
                                    @if($stats && $stats->graded_count > 0)
                                        Avg Grade: <span class="font-semibold">{{ number_format($stats->average_grade, 2) }}</span>
                                        ({{ $stats->graded_count }} graded / {{ $stats->submission_count }} submitted)
                                    @elseif($stats && $stats->submission_count > 0)
                                        ({{ $stats->submission_count }} submitted, none graded yet)
                                    @else
                                        (No submissions)
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">No assignments or quizzes found in this course.</p>
                    @endif
                </div>
            </div>

            {{-- Student Completion Rate --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <h3 class="text-lg font-medium mb-4">Student Assignment Completion Rate</h3>
                     @if(!empty($completionData))
                         <ul class="space-y-2">
                             @foreach($completionData as $studentName => $rate)
                                 <li class="text-sm border-b pb-2">
                                     <strong>{{ $studentName }}</strong>: {{ $rate }}% completed
                                 </li>
                             @endforeach
                         </ul>
                     @else
                         <p class="text-gray-500">No students enrolled or no assignments found.</p>
                     @endif
                </div>
            </div>

            {{-- Add more charts/stats here using Chart.js or similar --}}
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Progress Chart (Placeholder)</h3>
                     <p class="text-gray-500">
                         (Integrate a library like Chart.js here to visualize data like grade distribution,
                         completion over time, etc.)
                     </p>
                     {{-- Example: <canvas id="myChart"></canvas> --}}
                </div>
            </div>


        </div>
    </div>
     {{-- Include Chart.js library if needed --}}
     {{-- @push('scripts')
         <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
         <script>
           // Chart.js implementation based on passed data
         </script>
     @endpush --}}
</x-app-layout>