<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AjaxRequestsOnly
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
        if ($request->ajax()) {
            return $next($request);
        }
        else{
            abort(405, "Permission denied to Route");
        }
    }
}