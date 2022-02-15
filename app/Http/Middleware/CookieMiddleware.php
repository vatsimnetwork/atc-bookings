<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class CookieMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->cookie('secret-key');
        if (!$token || $token != env('SECRET_AUTH_KEY')) {
            return redirect()->route('key-auth.index', ['error' => 'no-auth']);
        }

        return $next($request);
    }
}
