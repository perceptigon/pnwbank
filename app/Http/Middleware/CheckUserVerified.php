<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckUserVerified
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
        if (Auth::guest())
            return redirect("/login");

        if (! Auth::user()->isVerified)
            return redirect("/notverified");

        return $next($request);
    }
}
