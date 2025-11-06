<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        // For API routes, never redirect - let it become a JSON 401
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // If you eventually add a web login page, update the route name here
        // return route('login');
        return null; // Avoid Route [login] not defined errors in API-only backend
    }
}
