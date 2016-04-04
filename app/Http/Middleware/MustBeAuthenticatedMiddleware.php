<?php

namespace App\Http\Middleware;

use Closure;

class MustBeAuthenticatedMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!session('citcuit.oauth')) {
            if ($request->is('/')) {
                $request->session()->flush();
                return view('non_api.home');
            } else {
                return redirect('');
            }
        }

        return $next($request);
    }

}
