<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow Super Admin and Onboarding users unconditionally
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        $subscriber = SubscriptionService::resolveSubscriber();
        if (!$subscriber) {
            return $next($request);
        }

        $hasActive = SubscriptionService::hasActiveSubscription($subscriber);

        if (!$hasActive) {
            // Terminate active session for clinic, doctor, or member
            if (Auth::guard('clinic')->check()) {
                Auth::guard('clinic')->logout();
            }
            if (Auth::guard('doctor')->check()) {
                Auth::guard('doctor')->logout();
            }
            if (Auth::guard('member')->check()) {
                Auth::guard('member')->logout();
            }

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your clinic subscription has expired or is inactive. Access has been locked.',
                ], 403);
            }

            return redirect()->route('login')->with('error', 'Your clinic subscription has expired or is inactive. Please contact your administrator to renew your plan.');
        }

        return $next($request);
    }
}
