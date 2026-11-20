<?php

namespace App\Http\Middleware;

use App\Services\Tracker;
use Closure;
use Illuminate\Http\Request;

class TrackVisit
{
    /** مسارات لا تُحسب كزيارات (عدّلها حسب مشروعك) */
    protected array $except = [
        'admin', 'admin/*', 'livewire/*', 'api/*', 'track/*', 'up',
        '_debugbar/*', 'telescope/*', 'horizon/*',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $purpose = strtolower((string) ($request->header('Purpose') ?: $request->header('Sec-Purpose')));

        if (
            $request->isMethod('GET')
            && !$request->ajax()
            && !$request->expectsJson()
            && !str_contains($purpose, 'prefetch')
            && !$request->is(...$this->except)
            && ($response->isSuccessful() || $response->isRedirection())
        ) {
            Tracker::touch($request);
        }

        return $response;
    }
}
