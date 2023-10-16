<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ServiceCharge;
use Illuminate\Support\Facades\DB;

class ServiceChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $startDate = request()->input('start_date');
        $endDate =  request()->input('end_date');

        
        if(request()->filled('start_date') && request()->filled('end_date'))
        {
            $sn =1;
            $serviceCharges = ServiceCharge::with('user')->where('user_id',auth()->user()->id)
                                            ->whereBetween('charge_date',[$startDate,$endDate])
                                            ->get(); 

            return view('report_service_charge', compact('serviceCharges', 'sn'));
        }
     
            return view('report_service_charge');
     
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
     * @param  \App\Models\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceCharge $serviceCharge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceCharge $serviceCharge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceCharge $serviceCharge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceCharge  $serviceCharge
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceCharge $serviceCharge)
    {
        //
    }
}
