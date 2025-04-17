<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Site Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-session-status class="mb-4" /> {{-- For success/error messages --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        {{-- Display form fields for each setting --}}
                        {{-- Example: Site Name --}}
                        <div class="mb-4">
                            <x-input-label for="site_name" :value="__('Site Name')" />
                            <x-text-input id="site_name" class="block mt-1 w-full" type="text" name="site_name" :value="old('site_name', $settings['site_name'] ?? '')" />
                            <x-input-error :messages="$errors->get('site_name')" class="mt-2" />
                        </div>

                         {{-- Example: Theme Choice --}}
                        <div class="mb-4">
                            <x-input-label for="theme" :value="__('Site Theme')" />
                            <select name="theme" id="theme" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="light" @selected(old('theme', $settings['theme'] ?? 'light') == 'light')>Light</option>
                                <option value="dark" @selected(old('theme', $settings['theme'] ?? 'light') == 'dark')>Dark</option>
                            </select>
                            <x-input-error :messages="$errors->get('theme')" class="mt-2" />
                        </div>

                         {{-- Example: Registration Open/Closed --}}
                        <div class="mb-4">
                             <label for="registration_open" class="inline-flex items-center">
                                <input id="registration_open" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                       name="registration_open" value="1"
                                       @checked(old('registration_open', $settings['registration_open'] ?? true))>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Allow New User Registrations') }}</span>
                            </label>
                             <input type="hidden" name="registration_open" value="0" @if(old('registration_open', $settings['registration_open'] ?? true)) disabled @endif/>
                            <x-input-error :messages="$errors->get('registration_open')" class="mt-2" />
                        </div>


                        {{-- Add more settings fields as needed --}}


                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Save Settings') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>