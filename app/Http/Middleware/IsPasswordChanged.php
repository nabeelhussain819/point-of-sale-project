<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsPasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    /**
     * Get the path the user should be redirected to when is_changed_password field is false.
     *
     * @param
     * @return string|null
     */

    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->is_changed_password == false) {
            return redirect('change-password');
        }
        return $next($request);
    }

}
