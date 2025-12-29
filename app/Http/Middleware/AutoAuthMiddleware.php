<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AutoAuthMiddleware
{
    /**
     * Handle an incoming request.
     * If user is not authenticated, automatically login with first available user
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            // For admin routes, try to find an admin user first
            if ($request->is('admin*')) {
                $user = User::where('is_admin', true)->first();
            }
            
            // If no admin user found (or not admin route), get first user
            if (!isset($user) || !$user) {
                $user = User::first();
            }
            
            // If still no user exists, create a demo user
            if (!$user) {
                // Generate a unique mobile number
                $baseMobile = '0912' . substr(time(), -7);
                $mobile = $baseMobile;
                $counter = 0;
                
                // Ensure mobile is unique
                while (User::where('mobile', $mobile)->exists() && $counter < 100) {
                    $mobile = '0912' . substr(time() . $counter, -7);
                    $counter++;
                }
                
                $user = User::create([
                    'name' => $request->is('admin*') ? 'مدیر دمو' : 'کاربر دمو',
                    'mobile' => $mobile,
                    'is_admin' => $request->is('admin*'),
                    'is_verified' => true,
                ]);
            }
            
            // Auto login the user
            auth()->login($user);
        }
        
        return $next($request);
    }
}










