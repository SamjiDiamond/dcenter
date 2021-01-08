<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyWallet;
use App\Models\SMSLog;
use App\Models\Transaction;
use App\Models\User;
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
//        return view('home');

//        $total_order=Transaction::where([['company_id','=',auth()->user()->company_id], ['type', '=', 'order']]);
//        $today_order=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['type', '=', 'order']]);
//        $yesterday_order=Transaction::where([['created_at', '=', Date::yesterday()], ['company_id','=',auth()->user()->company_id], ['type', '=', 'order']]);
//
//        $total_funding=Transaction::where([['company_id','=',auth()->user()->company_id], ['type', '=', 'funding']]);
//        $today_funding=Transaction::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id], ['type', '=', 'funding']]);
//        $yesterday_funding=Transaction::where([['created_at', '=', Date::yesterday()], ['company_id','=',auth()->user()->company_id], ['type', '=', 'funding']]);
//
//        $total_user=User::where([['company_id','=',auth()->user()->company_id]]);
//        $today_user=User::where([['created_at', '=', Date::today()], ['company_id','=',auth()->user()->company_id]]);
//        $yesterday_user=User::where([['created_at', '=', Date::yesterday()], ['company_id','=',auth()->user()->company_id]]);
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

        return view('dashboard');
    }
}
