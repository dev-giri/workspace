<?php

namespace Workspace\Http\Middleware;

use Closure;
use Workspace\User;
use TCG\Voyager\Models\Role;

class WorkspaceMiddleware
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

        if(!$this->updateRole()){
            if( $request->route()->getName() != 'workspace.cancelled' ){
                return redirect()->route('workspace.cancelled');
            }
        }
        return $next($request);
    }

    private function updateRole()
    {

        // if(!auth()->guest() && !auth()->user()->subscribed('main') && !auth()->user()->onTrial() && auth()->user()->role->name != 'admin' && (auth()->user()->subscribed('main') && !auth()->user()->subscription('main')->onGracePeriod()) && auth()->user()->role->name != 'cancelled' ){
        //     $inactive_user_role = Role::where('name', '=', 'cancelled')->first();
        //     auth()->user()->role_id = $inactive_user_role->id;
        //     auth()->user()->save();
        //     return false;
        // }

        // if(!auth()->guest() && !auth()->user()->subscribed('main') && auth()->user()->role->name != 'admin' && !auth()->user()->onTrial() && auth()->user()->role->name != 'cancelled'){
        //     $inactive_user_role = Role::where('name', '=', 'cancelled')->first();
        //     auth()->user()->role_id = $inactive_user_role->id;
        //     auth()->user()->save();
        //     return false;
        // }

        //return true;

        return true;

    }
}
