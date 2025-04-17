{{-- Reusable form part for submission create --}}

<!-- Text Content -->
<div class="mt-4">
    <x-input-label for="submission_text" :value="__('Text Submission (Optional)')" />
    <textarea id="submission_text" name="submission_text" rows="8"
              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              placeholder="Type your response here..."
              >{{ old('submission_text') }}</textarea>
    <x-input-error :messages="$errors->get('submission_text')" class="mt-2" />
</div>

<!-- File Upload -->
<div class="mt-4">
     <x-input-label for="submission_file" :value="__('File Upload (Optional)')" />
    <input id="submission_file" name="submission_file" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
     <x-input-error :messages="$errors->get('submission_file')" class="mt-2" />
     <p class="mt-1 text-xs text-gray-600">Max file size: 10MB. Allowed types: pdf, doc, docx, txt, zip.</p>
</div>