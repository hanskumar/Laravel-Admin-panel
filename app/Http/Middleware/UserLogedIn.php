<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserLogedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){

        if(($request->session()->get('logged_in') == 1) && ($request->session()->get('role') =='admin')){
            return $next($request);
        } else{
            return redirect('/no-access');
        }
       // abort(403);
    }
}
