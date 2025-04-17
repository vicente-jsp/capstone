<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Content to Course') }}: {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     {{-- Use multipart/form-data if handling file uploads --}}
                     <form method="POST" action="{{ route('educator.courses.contents.store', $course) }}" enctype="multipart/form-data">
                        @csrf
                        @include('educator.contents._form', ['content' => null, 'course' => $course])

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('educator.courses.show', $course) }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Add Content') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>