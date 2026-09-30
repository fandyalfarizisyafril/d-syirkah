<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()) {
            return redirect()->guest(route('admin.login'));
        }
        abort_unless($request->user()->is_admin, 403);

        return $next($request)->header('Cache-Control', 'no-store, private')->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
