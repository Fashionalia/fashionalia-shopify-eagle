<?php

namespace Eagle\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class EagleBasicAuthMiddleware
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $login    = $request->server('PHP_AUTH_USER');
        $password = $request->server('PHP_AUTH_PW');

        if ($login !== config('eagle.login') || $password !== config('eagle.pass')) {
            return new Response(\json_encode(['error' => 'Unauthenticated.']), Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
