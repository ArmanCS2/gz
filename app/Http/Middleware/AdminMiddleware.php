<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware should be used after 'auth' middleware
        // So we can safely assume user is authenticated
        if (!auth()->user()->is_admin) {
            abort(403, 'شما دسترسی به پنل مدیریت ندارید');
        }

        return $next($request);
    }
}

