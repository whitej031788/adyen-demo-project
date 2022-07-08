<?php

namespace App\Http\Middleware;

use Closure;

class CheckDemoSession
{
    public function handle($request, Closure $next)
    {
        if (!$request->session()->exists('demo_session')) {
            // user value cannot be found in session
            return redirect('/create-demo');
        }

        return $next($request);
    }
}
