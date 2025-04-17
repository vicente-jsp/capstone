{{-- resources/views/auth/choose-role.blade.php --}}
<x-guest-layout> {{-- Or use x-app-layout if you prefer the logged-in nav bar here --}}
    <div class="mb-4 text-center">
         <h2 class="text-2xl font-bold text-gray-700">Confirm Your Role</h2>
         <p class="text-sm text-gray-600 mt-2">
             Please select how you would like to proceed.
         </p>
    </div>

     <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" /> 

     <!-- Error Messages -->
     @if(session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 text-center">
            {{ session('error') }}
        </div>
     @endif

    <form method="POST" action="{{ route('choose-role.confirm') }}">
        @csrf

        <div class="space-y-4">
             {{-- Only show buttons for roles the user actually has --}}
             @if($user->isAdmin())
                <div>
                    <button type="submit" name="chosen_role" value="admin"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Proceed as Administrator
                    </button>
                </div>
             @endif

             @if($user->isEducator())
                <div>
                     <button type="submit" name="chosen_role" value="educator"
                             class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Proceed as Educator
                    </button>
                </div>
             @endif

             @if($user->isStudent())
                <div>
                    <button type="submit" name="chosen_role" value="student"
                             class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Proceed as Student
                    </button>
                </div>
             @endif

             {{-- Add a logout button as well --}}
             <div class="pt-4 border-t">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                     <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150">
                        Log Out
                    </button>
                 </form>
             </div>
        </div>
    </form>
</x-guest-layout>