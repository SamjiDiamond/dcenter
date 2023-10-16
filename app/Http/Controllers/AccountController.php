<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(request()->filled('introducer'), request()->filled('company'));
         if(request()->filled('introducer') && request()->filled('company'))
         {
            $accounts = User::with('company')->whereHas('company', fn($q) => $q->where('name','like', '%'. request()->query('company') . '%'))->whereHas('referrer', fn($q) => $q->where('first_name', 'like', '%'. request()->query('introducer'). '%'))->whereBetween('created_at',[request()->query('fromDate'), request()->query('toDate')])->get();
            
            $sn =1;
            
               return view('report_newaccount', compact('accounts','sn'));
        }
        return view('report_newaccount');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

}
