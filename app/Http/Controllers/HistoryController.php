<?php

namespace App\Http\Controllers;

use App\Models\CompanyWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function usertransaction(){
        if(auth()->user()->company_id==1) {
            $data['trans']=Transaction::paginate(10);
            $data['transCount']=Transaction::count();
        }else{
            $data['trans']=Transaction::where('company_id','=',auth()->user()->company_id)->paginate(10);
            $data['transCount']=Transaction::where('company_id','=',auth()->user()->company_id)->count();
        }
        $data['i']=1;

        return view('usertransaction_list', $data);

    }

    public function companywallet(){
        if(auth()->user()->company_id==1) {
            $data['trans']=CompanyWallet::paginate(10);
            $data['transCount']=CompanyWallet::count();
        }else{
            $data['trans']=CompanyWallet::where('company_id','=',auth()->user()->company_id)->paginate(10);
            $data['transCount']=CompanyWallet::where('company_id','=',auth()->user()->company_id)->count();
        }

        $data['i']=1;

        return view('companywallet_history', $data);

    }
}
