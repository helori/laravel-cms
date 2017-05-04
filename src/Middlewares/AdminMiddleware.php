<?php

namespace Helori\LaravelCms\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }
        return $next($request);
    }
}
