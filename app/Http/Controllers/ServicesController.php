<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ServicesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('subware');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->company_id==1) {
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->select('config_airtime.*', 'company.name as company')
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->select('config_data.*', 'company.name as company')
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->select('config_tv.*', 'company.name as company')
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->select('config_electricity.*', 'company.name as company')
                ->get();

            $transfer=DB::table("config_transfer")
                ->join("company", "company.id","=","config_transfer.company_id")
                ->select('config_transfer.*', 'company.name as company')
                ->get();

        }else{
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->join("config_default", "config_default.id","=","config_airtime.default_id")
                ->select('config_airtime.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->join("config_default", "config_default.id","=","config_data.default_id")
                ->select('config_data.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->join("config_default", "config_default.id","=","config_tv.default_id")
                ->select('config_tv.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->join("config_default", "config_default.id","=","config_electricity.default_id")
                ->select('config_electricity.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
            $transfer=DB::table("config_transfer")
                ->join("company", "company.id", "=", "config_transfer.company_id")
                ->join("config_default", "config_default.id", "=", "config_transfer.default_id")
                ->select('config_transfer.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
        }

        $cair=DB::table("config_airtime")
            ->join("company", "company.id","=","config_airtime.company_id")
            ->select('config_airtime.*', 'company.name as company')
            ->where('company_id','=',auth()->user()->company_id)
            ->count();

        $cdair= DB::table("config_default")
            ->where("type","=", "airtime")
            ->count();

        if($cdair>$cair){
            $synairtime= true;
        }else{
            $synairtime=false;
        }

        $cdata=DB::table("config_data")
            ->join("company", "company.id","=","config_data.company_id")
            ->select('config_data.*', 'company.name as company')
            ->where('company_id','=',auth()->user()->company_id)
            ->count();

        $cddata= DB::table("config_default")
            ->where("type","=", "data")
            ->count();

        if($cddata>$cdata){
            $syndata= true;
        }else{
            $syndata=false;
        }

        $ctv=DB::table("config_tv")
            ->join("company", "company.id","=","config_tv.company_id")
            ->select('config_tv.*', 'company.name as company')
            ->where('company_id','=',auth()->user()->company_id)
            ->count();

        $cdtv= DB::table("config_default")
            ->where("type","=", "tv")
            ->count();

        if($cdtv>$ctv){
            $syntv= true;
        }else{
            $syntv=false;
        }

        $celec=DB::table("config_electricity")
            ->join("company", "company.id","=","config_electricity.company_id")
            ->select('config_electricity.*', 'company.name as company')
            ->where('company_id','=',auth()->user()->company_id)
            ->count();

        $cdelec= DB::table("config_default")
            ->where("type","=", "electricity")
            ->count();

        if($cdelec>$celec){
            $synelec= true;
        }else{
            $synelec=false;
        }

        $ctran=DB::table("config_transfer")
            ->join("company", "company.id","=","config_transfer.company_id")
            ->select('config_transfer.*', 'company.name as company')
            ->where('company_id','=',auth()->user()->company_id)
            ->count();

        $cdtran= DB::table("config_default")
            ->where("type","=", "transfer")
            ->count();

        if($cdtran>$ctran){
            $syntran= true;
        }else{
            $syntran=false;
        }

        return view('config_services', ['airtime' => $airtime, 'data' =>$data, 'tv' => $tv, 'electricity' =>$electricity, 'transfer' =>$transfer, 'i'=>1, 'synairtime'=>$synairtime, 'syndata'=>$syndata, 'syntv'=>$syntv, 'synelec'=>$synelec, 'syntran'=>$syntran]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     */
    public function airtimeedit(Request $request)
    {
        if(auth()->user()->company_id==1) {
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->select('config_airtime.*', 'company.name as company')
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->select('config_data.*', 'company.name as company')
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->select('config_tv.*', 'company.name as company')
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->select('config_electricity.*', 'company.name as company')
                ->get();

            $transfer=DB::table("config_transfer")
                ->join("company", "company.id","=","config_transfer.company_id")
                ->select('config_transfer.*', 'company.name as company')
                ->get();

        }else{
            $airtime= DB::table("config_airtime")
                ->join("company", "company.id", "=", "config_airtime.company_id")
                ->join("config_default", "config_default.id", "=", "config_airtime.default_id")
                ->select('config_airtime.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $data= DB::table("config_data")
                ->join("company", "company.id", "=", "config_data.company_id")
                ->join("config_default", "config_default.id", "=", "config_data.default_id")
                ->select('config_data.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $tv= DB::table("config_tv")
                ->join("company", "company.id", "=", "config_tv.company_id")
                ->join("config_default", "config_default.id", "=", "config_tv.default_id")
                ->select('config_tv.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $electricity= DB::table("config_electricity")
                ->join("company", "company.id", "=", "config_electricity.company_id")
                ->join("config_default", "config_default.id", "=", "config_electricity.default_id")
                ->select('config_electricity.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
            $transfer= DB::table("config_transfer")
                ->join("company", "company.id", "=", "config_transfer.company_id")
                ->join("config_default", "config_default.id", "=", "config_transfer.default_id")
                ->select('config_transfer.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
        }
            $editd=DB::table("config_airtime")->where([['company_id','=',auth()->user()->company_id], ['id','=', $request->id]])->first();
            $type="airtime";

        return view('config_services', ['airtime' => $airtime, 'data' =>$data, 'tv' => $tv, 'electricity' =>$electricity, 'transfer' =>$transfer, 'edit'=>true , 'editd'=>$editd, 'type'=>$type, 'i'=>1]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function airtimeupdate(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        DB::table('config_airtime')
            ->where('id', $id)
            ->update(['price'=>$input['price'], 'status' => $input['status'] , 'desc' => $input['description']]);

        return redirect()->route('services.list')
            ->with('success','Service updated successfully');
    }

       /**
     * Show the form for editing the specified resource.
     *
     */
    public function dataedit(Request $request)
    {
        if(auth()->user()->company_id==1) {
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->select('config_airtime.*', 'company.name as company')
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->select('config_data.*', 'company.name as company')
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->select('config_tv.*', 'company.name as company')
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->select('config_electricity.*', 'company.name as company')
                ->get();

            $transfer=DB::table("config_transfer")
                ->join("company", "company.id","=","config_transfer.company_id")
                ->select('config_transfer.*', 'company.name as company')
                ->get();

        }else{
            $airtime= DB::table("config_airtime")
                ->join("company", "company.id", "=", "config_airtime.company_id")
                ->join("config_default", "config_default.id", "=", "config_airtime.default_id")
                ->select('config_airtime.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $data= DB::table("config_data")
                ->join("company", "company.id", "=", "config_data.company_id")
                ->join("config_default", "config_default.id", "=", "config_data.default_id")
                ->select('config_data.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $tv= DB::table("config_tv")
                ->join("company", "company.id", "=", "config_tv.company_id")
                ->join("config_default", "config_default.id", "=", "config_tv.default_id")
                ->select('config_tv.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $electricity= DB::table("config_electricity")
                ->join("company", "company.id", "=", "config_electricity.company_id")
                ->join("config_default", "config_default.id", "=", "config_electricity.default_id")
                ->select('config_electricity.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
            $transfer= DB::table("config_transfer")
                ->join("company", "company.id", "=", "config_transfer.company_id")
                ->join("config_default", "config_default.id", "=", "config_transfer.default_id")
                ->select('config_transfer.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
        }

        $editd=DB::table("config_data")->where([['company_id','=',auth()->user()->company_id], ['id','=', $request->id]])->first();
        $type="data";

        return view('config_services', ['airtime' => $airtime, 'data' =>$data, 'tv' => $tv, 'electricity' =>$electricity, 'transfer' =>$transfer, 'edit'=>true , 'editd'=>$editd, 'type'=>$type, 'i'=>1]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dataupdate(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        DB::table('config_data')
            ->where('id', $id)
            ->update(['price'=>$input['price'], 'status' => $input['status'] , 'desc' => $input['description']]);

        return redirect()->route('services.list')
            ->with('success','Service updated successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     */
    public function tvedit(Request $request)
    {
        if(auth()->user()->company_id==1) {
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->select('config_airtime.*', 'company.name as company')
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->select('config_data.*', 'company.name as company')
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->select('config_tv.*', 'company.name as company')
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->select('config_electricity.*', 'company.name as company')
                ->get();

            $transfer=DB::table("config_transfer")
                ->join("company", "company.id","=","config_transfer.company_id")
                ->select('config_transfer.*', 'company.name as company')
                ->get();

        }else{
            $airtime= DB::table("config_airtime")
                ->join("company", "company.id", "=", "config_airtime.company_id")
                ->join("config_default", "config_default.id", "=", "config_airtime.default_id")
                ->select('config_airtime.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $data= DB::table("config_data")
                ->join("company", "company.id", "=", "config_data.company_id")
                ->join("config_default", "config_default.id", "=", "config_data.default_id")
                ->select('config_data.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $tv= DB::table("config_tv")
                ->join("company", "company.id", "=", "config_tv.company_id")
                ->join("config_default", "config_default.id", "=", "config_tv.default_id")
                ->select('config_tv.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $electricity= DB::table("config_electricity")
                ->join("company", "company.id", "=", "config_electricity.company_id")
                ->join("config_default", "config_default.id", "=", "config_electricity.default_id")
                ->select('config_electricity.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
            $transfer= DB::table("config_transfer")
                ->join("company", "company.id", "=", "config_transfer.company_id")
                ->join("config_default", "config_default.id", "=", "config_transfer.default_id")
                ->select('config_transfer.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
        }

        $editd=DB::table("config_tv")->where([['company_id','=',auth()->user()->company_id], ['id','=', $request->id]])->first();
        $type="tv";

        return view('config_services', ['airtime' => $airtime, 'data' =>$data, 'tv' => $tv, 'electricity' =>$electricity, 'transfer' =>$transfer, 'edit'=>true , 'editd'=>$editd, 'type'=>$type, 'i'=>1]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tvupdate(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        DB::table('config_tv')
            ->where('id', $id)
            ->update(['price'=>$input['price'], 'status' => $input['status'] , 'desc' => $input['description']]);

        return redirect()->route('services.list')
            ->with('success','Service updated successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     */
    public function electedit(Request $request)
    {
        if(auth()->user()->company_id==1) {
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->select('config_airtime.*', 'company.name as company')
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->select('config_data.*', 'company.name as company')
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->select('config_tv.*', 'company.name as company')
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->select('config_electricity.*', 'company.name as company')
                ->get();

            $transfer=DB::table("config_transfer")
                ->join("company", "company.id","=","config_transfer.company_id")
                ->select('config_transfer.*', 'company.name as company')
                ->get();

        }else{
            $airtime= DB::table("config_airtime")
                ->join("company", "company.id", "=", "config_airtime.company_id")
                ->join("config_default", "config_default.id", "=", "config_airtime.default_id")
                ->select('config_airtime.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $data= DB::table("config_data")
                ->join("company", "company.id", "=", "config_data.company_id")
                ->join("config_default", "config_default.id", "=", "config_data.default_id")
                ->select('config_data.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $tv= DB::table("config_tv")
                ->join("company", "company.id", "=", "config_tv.company_id")
                ->join("config_default", "config_default.id", "=", "config_tv.default_id")
                ->select('config_tv.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $electricity= DB::table("config_electricity")
                ->join("company", "company.id", "=", "config_electricity.company_id")
                ->join("config_default", "config_default.id", "=", "config_electricity.default_id")
                ->select('config_electricity.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
            $transfer= DB::table("config_transfer")
                ->join("company", "company.id", "=", "config_transfer.company_id")
                ->join("config_default", "config_default.id", "=", "config_transfer.default_id")
                ->select('config_transfer.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
        }
        $editd=DB::table("config_electricity")->where([['company_id','=',auth()->user()->company_id], ['id','=', $request->id]])->first();
        $type="electricity";

        return view('config_services', ['airtime' => $airtime, 'data' =>$data, 'tv' => $tv, 'electricity' =>$electricity, 'transfer' =>$transfer, 'edit'=>true , 'editd'=>$editd, 'type'=>$type, 'i'=>1]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function electupdate(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        DB::table('config_electricity')
            ->where('id', $id)
            ->update(['price'=>$input['price'], 'status' => $input['status'] , 'desc' => $input['description']]);

        return redirect()->route('services.list')
            ->with('success','Service updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function transferedit(Request $request)
    {
        if(auth()->user()->company_id==1) {
            $airtime=DB::table("config_airtime")
                ->join("company", "company.id","=","config_airtime.company_id")
                ->select('config_airtime.*', 'company.name as company')
                ->get();

            $data=DB::table("config_data")
                ->join("company", "company.id","=","config_data.company_id")
                ->select('config_data.*', 'company.name as company')
                ->get();

            $tv=DB::table("config_tv")
                ->join("company", "company.id","=","config_tv.company_id")
                ->select('config_tv.*', 'company.name as company')
                ->get();

            $electricity=DB::table("config_electricity")
                ->join("company", "company.id","=","config_electricity.company_id")
                ->select('config_electricity.*', 'company.name as company')
                ->get();

            $transfer=DB::table("config_transfer")
                ->join("company", "company.id","=","config_transfer.company_id")
                ->select('config_transfer.*', 'company.name as company')
                ->get();

        }else{
            $airtime= DB::table("config_airtime")
                ->join("company", "company.id", "=", "config_airtime.company_id")
                ->join("config_default", "config_default.id", "=", "config_airtime.default_id")
                ->select('config_airtime.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $data= DB::table("config_data")
                ->join("company", "company.id", "=", "config_data.company_id")
                ->join("config_default", "config_default.id", "=", "config_data.default_id")
                ->select('config_data.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $tv= DB::table("config_tv")
                ->join("company", "company.id", "=", "config_tv.company_id")
                ->join("config_default", "config_default.id", "=", "config_tv.default_id")
                ->select('config_tv.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();

            $electricity= DB::table("config_electricity")
                ->join("company", "company.id", "=", "config_electricity.company_id")
                ->join("config_default", "config_default.id", "=", "config_electricity.default_id")
                ->select('config_electricity.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
            $transfer= DB::table("config_transfer")
                ->join("company", "company.id", "=", "config_transfer.company_id")
                ->join("config_default", "config_default.id", "=", "config_transfer.default_id")
                ->select('config_transfer.*', 'company.name as company', 'config_default.price as defaultprice')
                ->where('company_id','=',auth()->user()->company_id)
                ->get();
        }
        $editd=DB::table("config_transfer")->where([['company_id','=',auth()->user()->company_id], ['id','=', $request->id]])->first();
        $type="transfer";

        return view('config_services', ['airtime' => $airtime, 'data' =>$data, 'tv' => $tv, 'electricity' =>$electricity, 'transfer' =>$transfer, 'edit'=>true , 'editd'=>$editd, 'type'=>$type, 'i'=>1]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function transferupdate(Request $request, $id)
    {
        $this->validate($request, [
            'price' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();

        DB::table('config_transfer')
            ->where('id', $id)
            ->update(['price'=>$input['price'], 'status' => $input['status'] , 'desc' => $input['description']]);

        return redirect()->route('services.list')
            ->with('success','Service updated successfully');
    }

    public function synair(){
        $cdair= DB::table("config_default")
            ->where("type","=", "airtime")
            ->get();

        foreach ($cdair as $item) {
            DB::table("config_airtime")->insertOrIgnore(['company_id'=>auth()->user()->company_id, 'code'=>$item->code, 'price'=>$item->price, 'desc'=>$item->desc, 'identifier'=>$item->code.auth()->user()->company_id, 'default_id'=>$item->id]);
        }

        return redirect()->route('services.list')
            ->with('success','Services sync successfully');
    }

    public function syndata(){
        $cddata= DB::table("config_default")
            ->where("type","=", "data")
            ->get();

        foreach ($cddata as $item) {
            DB::table("config_data")->insertOrIgnore(['company_id'=>auth()->user()->company_id, 'code'=>$item->code, 'price'=>$item->price, 'desc'=>$item->desc, 'network'=>$item->network, 'identifier'=>$item->code.auth()->user()->company_id, 'default_id'=>$item->id]);
        }

        return redirect()->route('services.list')
            ->with('success','Services sync successfully');
    }

    public function syntv(){
        $cdtv= DB::table("config_default")
            ->where("type","=", "tv")
            ->get();

        foreach ($cdtv as $item) {
            DB::table("config_tv")->insertOrIgnore(['company_id'=>auth()->user()->company_id, 'code'=>$item->code, 'price'=>$item->price, 'desc'=>$item->desc, 'provider'=>$item->network, 'identifier'=>$item->code.auth()->user()->company_id, 'default_id'=>$item->id]);
        }

        return redirect()->route('services.list')
            ->with('success','Services sync successfully');
    }

    public function synelec(){
        $cdelec= DB::table("config_default")
            ->where("type","=", "electricity")
            ->get();

        foreach ($cdelec as $item) {
            DB::table("config_electricity")->insertOrIgnore(['company_id'=>auth()->user()->company_id, 'code'=>$item->code, 'price'=>$item->price, 'desc'=>$item->desc, 'identifier'=>$item->code.auth()->user()->company_id, 'default_id'=>$item->id]);
        }

        return redirect()->route('services.list')
            ->with('success','Services sync successfully');
    }

    public function syntran(){
        $cdtran= DB::table("config_default")
            ->where("type","=", "transfer")
            ->get();

        foreach ($cdtran as $item) {
            DB::table("config_transfer")->insertOrIgnore(['company_id'=>auth()->user()->company_id, 'code'=>$item->code, 'price'=>$item->price, 'desc'=>$item->desc, 'identifier'=>$item->code.auth()->user()->company_id, 'default_id'=>$item->id]);
        }

        return redirect()->route('services.list')
            ->with('success','Services sync successfully');
    }
}
