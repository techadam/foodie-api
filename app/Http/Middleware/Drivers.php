<?php

namespace App\Http\Middleware;

use Closure;

class Drivers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user->role == 'drivers') {
            return $next($request);
        } else {
            return response()->json('Unauthorized', 401);

        }
    }
}
