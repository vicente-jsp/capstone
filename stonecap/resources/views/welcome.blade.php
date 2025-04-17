<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel LMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Or link your compiled CSS --}}
        <style>
            /* Add some basic styling */
            body { font-family: 'Figtree', sans-serif; }
            .center-content {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background-color: #f3f4f6; /* Tailwind gray-100 */
            }
            .title {
                font-size: 2.5rem; /* Larger title */
                font-weight: 600;
                margin-bottom: 2rem;
                 color: #1f2937; /* Tailwind gray-800 */
            }
            .links a {
                display: inline-block;
                margin: 0 1rem;
                padding: 0.75rem 1.5rem;
                border-radius: 0.375rem; /* rounded-md */
                text-decoration: none;
                font-weight: 600;
                transition: background-color 0.2s ease-in-out;
            }
            .login-link {
                background-color: #2563eb; /* Tailwind blue-600 */
                color: white;
            }
            .login-link:hover {
                background-color: #1d4ed8; /* Tailwind blue-700 */
            }
             .register-link {
                background-color: #4b5563; /* Tailwind gray-600 */
                color: white;
            }
            .register-link:hover {
                 background-color: #374151; /* Tailwind gray-700 */
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="center-content">
            <div class="title">
                Welcome to {{ config('app.name', 'LMS Platform') }}
            </div>

            <div class="links">
                @guest
                    <a href="{{ route('login') }}" class="login-link">Login</a>
                    <a href="{{ route('register') }}" class="register-link">Register</a>
                @else
                    {{-- If user is somehow logged in but hasn't chosen role, send them there --}}
                    <a href="{{ route('choose-role.show') }}" class="login-link">Continue to Dashboard</a>
                     <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <a href="{{ route('logout') }}"
                                class="register-link"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                @endguest
            </div>
        </div>
    </body>
</html>
