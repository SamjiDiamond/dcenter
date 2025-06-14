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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VirtualAccount  $virtualAccount
     * @return \Illuminate\Http\Response
     */
    public function show(VirtualAccount $virtualAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VirtualAccount  $virtualAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(VirtualAccount $virtualAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VirtualAccount  $virtualAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VirtualAccount $virtualAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VirtualAccount  $virtualAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(VirtualAccount $virtualAccount)
    {
        //
    }
}
