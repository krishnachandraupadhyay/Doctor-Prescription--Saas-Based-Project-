<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireFeature
{
    public function handle(Request $request, Closure $next, string $featureCode): Response
    {
        if (!SubscriptionService::hasFeature($featureCode)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "This feature [{$featureCode}] is not included in your active subscription plan. Please upgrade your plan.",
                ], 403);
            }

            return redirect()->back()->with('error', "Your current subscription plan does not include access to this feature. Please contact administration to upgrade.");
        }

        return $next($request);
    }
}
