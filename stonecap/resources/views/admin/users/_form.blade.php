{{-- Used in create.blade.php and edit.blade.php --}}
{{-- Requires $user variable (can be null for create) --}}

<!-- Name -->
<div>
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user?->name)" required autofocus autocomplete="name" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Email -->
<div class="mt-4">
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user?->email)" required autocomplete="username" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<!-- Role -->
<div class="mt-4">
    <x-input-label for="role" :value="__('Role')" />
    <select name="role" id="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="student" @selected(old('role', $user?->role) == 'student')>Student</option>
        <option value="educator" @selected(old('role', $user?->role) == 'educator')>Educator</option>
        <option value="admin" @selected(old('role', $user?->role) == 'admin')>Admin</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>


<!-- Password -->
<div class="mt-4">
    <x-input-label for="password" :value="__('Password')" />
    <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    {{-- Only require password on create --}}
                    {{ !$user ? 'required' : '' }}
                    autocomplete="new-password" />
    @if($user)
     <p class="mt-1 text-xs text-gray-600">Leave blank to keep the current password.</p>
    @endif
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<!-- Confirm Password -->
<div class="mt-4">
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                     {{ !$user ? 'required' : '' }}
                     autocomplete="new-password" />
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>