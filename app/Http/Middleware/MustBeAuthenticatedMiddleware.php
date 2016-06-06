<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;

class MustBeAuthenticatedMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Cookie::get('citcuit_session1')) {
            if ($request->is('/')) {
                $request->session()->flush();
                Cookie::queue(Cookie::forget('citcuit_session1'));
                Cookie::queue(Cookie::forget('citcuit_session2'));
                Cookie::queue(Cookie::forget('citcuit_session3'));

                return view('non_api.home');
            } else {
                return redirect('/');
            }
        }

        return $next($request);
    }

}
