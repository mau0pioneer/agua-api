<?php

namespace App\Http\Middleware;

use App\Helpers\APIHelper;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            APIHelper::responseFailed([
                'message' => 'Unauthorized',
                'errors' => [
                    'jwt' => [
                        'Json Web Token is invalid'
                    ]
                ]
            ], 401);
        }

        return $next($request);
    }
}
