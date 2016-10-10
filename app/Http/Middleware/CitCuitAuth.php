<?php

namespace App\Http\Middleware;

use Closure;

class CitCuitAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session('auth')) {
            if ($request->is('/')) {
                $request->session()->flush();

                return response()->view('non_api.home');
            } else {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
