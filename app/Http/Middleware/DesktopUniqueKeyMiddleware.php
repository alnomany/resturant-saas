<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Branch;

class DesktopUniqueKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tableTrackKey = $request->header('X-TABLETRACK-KEY');

        if (!$tableTrackKey && env('APP_ENV') !== 'development') {
            return response()->json(['message' => 'No authentication key found'], 401);
        }

        if (env('APP_ENV') == 'development') {
            $branch = Branch::first();
        } else {
            $branch = Branch::where('unique_hash', $tableTrackKey)->first();
        }

        if (!$branch) {
            return response()->json(['message' => 'Invalid authentication key'], 401);
        }

        // Attach branch to request for controller use
        $request->merge(['branch' => $branch]);

        return $next($request);
    }
}
