<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $roles = [];
        $roles = explode('|', $type);

        for ($i = 0; $i < sizeof($roles); $i++) {
            if (Auth::user()->type == $roles[$i]) {
                return $next($request);
            }
        }
        return error('You have no access rights to perform this action', type: 'unauthenticated');
    }
}
