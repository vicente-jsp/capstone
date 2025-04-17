{{-- Requires $course (can be null) and $educators --}}

<!-- Title -->
<div>
    <x-input-label for="title" :value="__('Course Title')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $course?->title)" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<!-- Description -->
<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4"
              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              >{{ old('description', $course?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<!-- Educator -->
<div class="mt-4">
    <x-input-label for="educator_id" :value="__('Assigned Educator')" />
    <select name="educator_id" id="educator_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">-- No Assigned Educator --</option>
        @foreach($educators as $id => $name)
            <option value="{{ $id }}" @selected(old('educator_id', $course?->educator_id) == $id)>
                {{ $name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('educator_id')" class="mt-2" />
</div>

<!-- Status -->
<div class="mt-4">
     <label for="is_active" class="inline-flex items-center">
        <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               name="is_active" value="1" {{-- Value is 1 when checked --}}
               @checked(old('is_active', $course?->is_active ?? true))> {{-- Checked by default on create --}}
        <span class="ms-2 text-sm text-gray-600">{{ __('Active Course') }}</span>
    </label>
     {{-- Need hidden input for when checkbox is unchecked --}}
     <input type="hidden" name="is_active" value="0" @if(old('is_active', $course?->is_active ?? true)) disabled @endif/>
    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
</div>

{{-- Add other fields like theme_color here if needed --}}