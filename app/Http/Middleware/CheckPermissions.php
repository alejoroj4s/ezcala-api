<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckPermissions
{
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::check() && Auth::user()->can($permission)) {
            return $next($request);
        }

        Session::flash('error', 'No tienes permisos para acceder a este recurso');
        return redirect()->back();
    }
}
