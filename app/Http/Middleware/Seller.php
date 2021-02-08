<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class Seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
       if (Auth::check()) {
           if (auth()->user()->userType == "Admin") {
                return $next($request);
           }else{
            return redirect('/');
           }
       }else{
        abort(401);
       }
    }
}
