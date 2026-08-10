<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Company;
use App\Models\CompanyWallet;
use App\Models\Faq;
use App\Models\SMSLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Verification;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['month_order']=Transaction::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m").'%'], ['company_id','=',auth()->user()->company_id]])->count();
        $data['today_order']=Transaction::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m-d").'%'], ['company_id','=',auth()->user()->company_id]])->count();

        $data['month_user']=User::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m").'%'], ['company_id','=',auth()->user()->company_id]])->count();
        $data['today_user']=User::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m-d").'%'], ['company_id','=',auth()->user()->company_id]])->count();

        $data['month_funding']=Transaction::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m").'%'], ['company_id','=',auth()->user()->company_id], ['code', '=', 'fund_wallet']])->sum('amount');
        $data['today_funding']=Transaction::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m-d").'%'], ['company_id','=',auth()->user()->company_id], ['code', '=', 'fund_wallet']])->sum('amount');

        $data['month_consume']=Transaction::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m").'%'], ['company_id','=',auth()->user()->company_id], ['type','=','debit']])->sum('amount');
        $data['today_consume']=Transaction::where([['created_at', 'LIKE', '%'.Carbon::now()->format("y-m-d").'%'], ['company_id','=',auth()->user()->company_id], ['type','=','debit']])->sum('amount');

        $data['transactions']=Transaction::where([['company_id','=',auth()->user()->company_id]])->latest()->limit(10)->get();

        $data['verifications']=Verification::where([['company_id','=',auth()->user()->company_id]])->latest()->limit(5)->get();

        $data['users']=User::where([['company_id','=',auth()->user()->company_id]])->latest()->limit(5)->get();

        // Recent activity on the dashboard only shows the current day's entries;
        // the full history lives on the audit trail report page ("See more").
        $data['audits']=AuditTrail::where([['company_id','=',auth()->user()->company_id]])
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->limit(5)
            ->get();

        $data['faqs']=Faq::where([['company_id','=',auth()->user()->company_id]])->latest()->limit(5)->get();

//

//
//        $total_wallet_funding=CompanyWallet::where([['type', '=', 'funding'], ['company_id','=',auth()->user()->company_id]]);
//        $current_revenue=Company::where([['company_id','=',auth()->user()->company_id]]);
//        $yesterday_revenue=CompanyWallet::where([['created_at', '=', Date::yesterday()], ['company_id','=',auth()->user()->company_id]]);
//
//        $transactions=Transaction::where([['company_id','=',auth()->user()->company_id], ['type', '=', 'order']])->get();
//        $smslog=SMSLog::where([['company_id','=',auth()->user()->company_id]])->get();

//        $data_order=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['name', '=', 'data']]);
//        $tv_order=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['name', '=', 'tv_subscription']]);
//        $airtime_order=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['name', '=', 'airtime']]);
//        $transfer_order=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['name', '=', 'transfer']]);
//        $electricity_order=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['name', '=', 'electricity']]);
//
//        $gt="";
//        for($x = 0; $x <= 7; $x++){
//            $modifiedImmutable = CarbonImmutable::now()->add('-'.$x, 'day');
//            $imdf =substr($modifiedImmutable, 0, 10);
//
//            $gt=Transaction::where([['created_at', '=', $modifiedImmutable], ['company_id','=',auth()->user()->company_id], ['type', '=', 'order']])->count();
//
//            $ft=Transaction::where([['created_at', '=', $modifiedImmutable], ['company_id','=',auth()->user()->company_id], ['type', '=', 'funding']])->count();
//
//            $imdf =substr($modifiedImmutable, 8, 2);
//
//            $gs="{ y: '".$modifiedImmutable."', a: ".$gt.", b: ".$ft." }";
//
//            if($x<7){
//                $gs=$gs+',';
//            }
//            $gt=$gt+$gs;
//        }

        return view('dashboard', $data);
    }
}
