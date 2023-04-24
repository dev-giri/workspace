<?php

namespace Workspace\Http\Middleware;

use Closure;
use TCG\Voyager\Models\Role;

class Cancelled
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
        if( auth()->user()->role->name == 'cancelled' ){
            return redirect()->route('workspace.cancelled');
        }

        return $next($request);
    }
}
