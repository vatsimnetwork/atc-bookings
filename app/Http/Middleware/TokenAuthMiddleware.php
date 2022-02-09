<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class TokenAuthMiddleware
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
        $token = $request->bearerToken();
        if (!$token) {
            return response_unauth(['error' => 'Missing Bearer Token']);
        }

        $key_model = ApiKey::query()->where('key', $token)->first();
        if (!$key_model) {
            return response_unauth(['error' => 'Token does not exist']);
        }

        $request->user = $key_model;

        return $next($request);
    }
}
