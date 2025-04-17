<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class, // Default is often commented out
        \Illuminate\Http\Middleware\TrustProxies::class, // Corrected namespace
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class, // Corrected namespace
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class, // Corrected namespace
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class, // Corrected namespace
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Often added by starter kits like Breeze/Jetstream
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // Corrected namespace
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Middleware to handle maintenance mode for web requests
             \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class, // Corrected namespace
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // If using Sanctum SPA auth
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
             // Middleware to handle maintenance mode for api requests
             \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class, // Corrected namespace
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware
     * to routes and groups. Keys should be lowercase and snake_case.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        // NOTE: Aliases map a string key to the *correct* class namespace
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class, // Corrected namespace
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class, // Corrected namespace
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // --- LMS Role Middleware (These ARE in App\Http\Middleware) ---
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        'educator' => \App\Http\Middleware\EnsureUserIsEducator::class,
        'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
        // --- End LMS Role Middleware ---
    ];

    /**
      * The priority-sorted list of middleware.
      *
      * This forces non-global middleware to always be in the given order.
      * Forces session middleware to load first globally.
      *
      * @var string[]
      */
     protected $middlewarePriority = [
         \Illuminate\Cookie\Middleware\EncryptCookies::class, // Corrected namespace
         \Illuminate\Session\Middleware\StartSession::class,
         \Illuminate\View\Middleware\ShareErrorsFromSession::class,
         \Illuminate\Auth\Middleware\Authenticate::class, // Corrected namespace (references the alias 'auth' target)
         \Illuminate\Session\Middleware\AuthenticateSession::class,
         \Illuminate\Routing\Middleware\SubstituteBindings::class,
         \Illuminate\Auth\Middleware\Authorize::class,
     ];
}