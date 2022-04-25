<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DumpDataGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('token') !== config('app.dump_data_token')) {
            abort(403);
        }
        return $next($request);
    }
}
