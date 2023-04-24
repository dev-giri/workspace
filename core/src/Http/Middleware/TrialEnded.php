<?php

namespace Workspace\Http\Middleware;

use Closure;

class TrialEnded
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

        if( intval(setting('billing.trial_days', 0)) > 0 && !setting('billing.card_upfront') && auth()->user()->daysLeftOnTrial() < 1 ){
            if(auth()->user()->role->name == 'trial' && ($request->route()->getName() != 'workspace.trial_over' && $request->route()->getName() != 'workspace.settings') ){
                return redirect()->route('workspace.trial_over');
            }
        }

        return $next($request);
    }
}
