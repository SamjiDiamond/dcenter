<?php

namespace App\Http\Middleware;

use App\Models\Company;
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

        $comp=Company::find(Auth::user()->company_id);
        if ($request->user() && ! $comp->subscribed('standard')) {
            // This user is not a paying customer...
            return redirect('billing');
        }
        return $next($request);
    }
}
