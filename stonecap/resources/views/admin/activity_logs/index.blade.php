<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <x-session-status class="mb-4" />

            {{-- Filter Form --}}
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label for="user_id" :value="__('User')" />
                                <select name="user_id" id="user_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="">-- All Users --</option>
                                    @foreach($users as $id => $name)
                                        <option value="{{ $id }}" @selected(request('user_id') == $id)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <x-input-label for="action" :value="__('Action Contains')" />
                                <x-text-input id="action" class="block mt-1 w-full text-sm" type="text" name="action" :value="request('action')" />
                            </div>
                             <div>
                                <x-input-label for="date_from" :value="__('Date From')" />
                                <x-text-input id="date_from" class="block mt-1 w-full text-sm" type="date" name="date_from" :value="request('date_from')" />
                            </div>
                            <div>
                                <x-input-label for="date_to" :value="__('Date To')" />
                                <x-text-input id="date_to" class="block mt-1 w-full text-sm" type="date" name="date_to" :value="request('date_to')" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4 space-x-2">
                             <a href="{{ route('admin.activity-logs.index') }}" class="text-sm text-gray-600 hover:underline">Reset</a>
                             <x-primary-button>Filter</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Log Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Timestamp</th>
                                    <th scope="col" class="px-6 py-3">User</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                    <th scope="col" class="px-6 py-3">Target</th>
                                    <th scope="col" class="px-6 py-3">Details</th>
                                    <th scope="col" class="px-6 py-3">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="px-6 py-4">
                                        @if($log->user)
                                        <a href="{{ route('admin.users.show', $log->user) }}" class="text-blue-600 hover:underline">{{ $log->user->name }}</a>
                                        @else
                                        System/Guest
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $log->action }}</td>
                                    <td class="px-6 py-4">
                                         @if($log->loggable)
                                             {{ class_basename($log->loggable_type) }}:{{ $log->loggable_id }}
                                             {{-- Maybe add links here if applicable --}}
                                         @else
                                             N/A
                                         @endif
                                    </td>
                                     <td class="px-6 py-4">
                                         @if($log->details)
                                            <pre class="text-xs bg-gray-100 p-1 rounded max-w-xs overflow-x-auto">{{ json_encode($log->details, JSON_PRETTY_PRINT) }}</pre>
                                         @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $log->ip_address ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No activity logs found matching your criteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>