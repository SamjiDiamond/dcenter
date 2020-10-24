<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index() {
        $companys = Company::where('status', 'active')->select('id','name')->get();
        return response()->json(['status' => 1, 'message'=>'Available companies', 'data'=> $companys]);
    }

    public function getcompany() {
        $company = DB::table('company')->where('id', Auth::user()->company_id)->first();
        if($company){
//            latitude: 37.78825, longitude: -122.4324,
            return response()->json(['status' => 1, 'message'=>'Company details fetched successfully', 'address'=> $company->address, 'phoneno'=> $company->phoneno, 'email'=> $company->email, 'logo'=> "company/image/". $company->logo, 'name'=> $company->name, 'gps'=>$company->gps]);
        }else{
            return response()->json(['status' => 0, 'message'=>'Company does not exist']);
        }
    }
}
