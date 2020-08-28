<?php

namespace App\Http\Middleware;

use Closure;

class CheckHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ( auth()->user()->role != $role ) {
            abort(401, 'Not authorized');
        }

        return $next($request);
    }
}
