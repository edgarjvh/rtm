<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Organization
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
        if (Auth::user()->organization_id > 0){
            return $next($request);
        }else{
            return redirect('/organization-setup')->with(['owner' => Auth::user()->organization_owner]);
        }
    }
}
