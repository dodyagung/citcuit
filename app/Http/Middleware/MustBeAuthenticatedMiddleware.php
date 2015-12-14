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
//        print_r($request->session()->all()); die();
        
        if (!session('citcuit.oauth.user_id')) {
            if ($request->is('/')) {
                $request->session()->flush();
                return view('non_api.home');
            } else {
                return redirect(url());
            }
        }

        return $next($request);
    }

}
