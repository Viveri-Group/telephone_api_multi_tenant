<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(app()->environment('production', 'staging')) {
            Log::info('Request Headers:', $request->headers->all());
        }

        return $next($request);
    }
}
