<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsEmployee
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
        if (Auth::check()) {
            if(Auth::user()->id_group == 2 || Auth::user()->id_group == 5 || Auth::user()->id_group == 8 || Auth::user()->id_group == 6 || Auth::user()->id_group == 1)
            return $next($request);
            else{
            return redirect('/');
            }
        }else {
            Auth::logout();
            return redirect('/');
        }
    }
}
