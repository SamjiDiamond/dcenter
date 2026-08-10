<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $planStatus = Subscription::where('company_id', auth()->user()->company_id)->pluck('plan_status')->last();

        $trialSub = Subscription::where('company_id', auth()->user()->company_id)->first();

        if($planStatus == "pending" && $trialSub->trial_status == "deactivated"){
            return redirect('billing')->withToast('You have no active subscription. Kindly subscribe to a new plan.', 'warning');
        }

        return $next($request);
    }

     /* if ($request->user() && !$comp->subscribed('main') && !$comp->onGenericTrial()) {
            // This user is not a paying customer...
            return redirect('billing');
        }

        */
}
