<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        Log::info('Request:', [
            'url' => $request->fullUrl(),
            'user' => auth()->check() ? auth()->user()->email : 'guest',
            'user_id' => auth()->id(),
        ]);

        $response = $next($request);

        if ($response->getStatusCode() === 403) {
            Log::error('403 Forbidden:', [
                'url' => $request->fullUrl(),
                'user' => auth()->user()?->email,
            ]);
        }

        return $response;
    }
}
