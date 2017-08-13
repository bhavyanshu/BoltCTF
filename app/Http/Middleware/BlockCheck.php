<?php

namespace BoltCTF\Http\Middleware;

use Closure;

class BlockCheck
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
      if ($request->user()->blocked === 1) { //if user is blocked
        return response()->View('auth.users.blocked');
      }
      else if ($request->user()->confirmed === 0) { //if user has not verified email
        return response()->view('auth.users.unverified');
      }
      else {
        return $next($request);
      }
    }
}
