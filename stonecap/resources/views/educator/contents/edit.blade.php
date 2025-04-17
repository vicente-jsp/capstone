<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Content') }}: {{ $content->title }}
             <span class="text-base font-normal">(Course: {{ $content->course->title }})</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <form method="POST" action="{{ route('educator.contents.update', $content) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        @include('educator.contents._form', ['content' => $content, 'course' => $content->course])

                        <div class="flex items-center justify-end mt-6">
                             <a href="{{ route('educator.courses.show', $content->course) }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Content') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>