{{-- Requires $content (can be null) and $course --}}

<!-- Title -->
<div>
    <x-input-label for="title" :value="__('Content Title')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $content?->title)" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<!-- Description -->
<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="3"
              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              >{{ old('description', $content?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<!-- Type -->
<div class="mt-4">
    <x-input-label for="type" :value="__('Content Type')" />
    <select name="type" id="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="resource" @selected(old('type', $content?->type) == 'resource')>Resource (File/Link/Text)</option>
        <option value="assignment" @selected(old('type', $content?->type) == 'assignment')>Assignment</option>
        <option value="quiz" @selected(old('type', $content?->type) == 'quiz') disabled>Quiz (Not Implemented)</option>
        <option value="forum_topic" @selected(old('type', $content?->type) == 'forum_topic') disabled>Forum Topic (Not Implemented)</option>
        <option value="announcement" @selected(old('type', $content?->type) == 'announcement')>Announcement</option>
    </select>
    <x-input-error :messages="$errors->get('type')" class="mt-2" />
</div>

<!-- Content Data (URL, Text, Quiz Settings etc) -->
<div class="mt-4">
    <x-input-label for="content_data" :value="__('Content Data (URL, Text, Settings JSON etc.)')" />
    <textarea id="content_data" name="content_data" rows="4"
              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              placeholder="Enter URL for resource, text for announcement, or JSON for quiz settings..."
              >{{ old('content_data', $content?->content_data) }}</textarea>
    <x-input-error :messages="$errors->get('content_data')" class="mt-2" />
    <p class="mt-1 text-xs text-gray-600">Use this field for resource links, announcement text, etc. Leave blank if uploading a file resource.</p>
</div>

<!-- Resource File Upload -->
<div class="mt-4">
     <x-input-label for="resource_file" :value="__('Resource File (for Resource type only)')" />
    <input id="resource_file" name="resource_file" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
     <x-input-error :messages="$errors->get('resource_file')" class="mt-2" />
      @if($content?->file_path)
         <div class="mt-2 text-sm text-gray-600">
            Current file: {{ basename($content->file_path) }}
             <label for="remove_file" class="ml-4 inline-flex items-center">
                <input id="remove_file" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remove_file" value="1">
                <span class="ms-1 text-xs">{{ __('Remove current file') }}</span>
            </label>
         </div>
     @endif
     <p class="mt-1 text-xs text-gray-600">Max file size: 20MB. Only used if type is 'Resource'. Replaces existing file if uploaded.</p>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <!-- Order -->
    <div>
        <x-input-label for="order" :value="__('Display Order')" />
        <x-text-input id="order" class="block mt-1 w-full" type="number" name="order" :value="old('order', $content?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>

    <!-- Visibility -->
    <div>
         <label for="is_visible" class="inline-flex items-center mt-6">
            <input id="is_visible" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                   name="is_visible" value="1"
                   @checked(old('is_visible', $content?->is_visible ?? false))>
            <span class="ms-2 text-sm text-gray-600">{{ __('Visible to Students') }}</span>
        </label>
         <input type="hidden" name="is_visible" value="0" @if(old('is_visible', $content?->is_visible ?? false)) disabled @endif/>
        <x-input-error :messages="$errors->get('is_visible')" class="mt-2" />
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <!-- Available From -->
    <div>
        <x-input-label for="available_from" :value="__('Available From (Optional)')" />
        <x-text-input id="available_from" class="block mt-1 w-full" type="datetime-local" name="available_from" :value="old('available_from', $content?->available_from?->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('available_from')" class="mt-2" />
    </div>

     <!-- Due Date -->
    <div>
        <x-input-label for="due_date" :value="__('Due Date (Optional)')" />
        <x-text-input id="due_date" class="block mt-1 w-full" type="datetime-local" name="due_date" :value="old('due_date', $content?->due_date?->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
    </div>
</div>