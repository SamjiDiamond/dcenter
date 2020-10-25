<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AirtimeConfig;
use App\Models\DataConfig;
use App\Models\ElectricityConfig;
use App\Models\TvConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function transactions() {
        $trans = Transaction::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        if($trans->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'No Transaction yet']);
        }
        return response()->json(['status' => 1, 'message'=>'Transactions loaded successfully', 'data'=> $trans]);
    }

    public function transaction($id) {
        $tran = Transaction::where([['user_id', Auth::id()], ['id', $id]])->first();
        if(!$tran){
            return response()->json(['status' => 0, 'message'=>'Transaction not found']);
        }
        return response()->json(['status' => 1, 'message'=>'Transaction fetched successfully', 'data'=> $tran]);
    }

    public function airtimeConfig() {
        $user = Auth::user();
        $config = AirtimeConfig::where([['company_id', $user->company_id], ['status', 1]])->select('code','price')->get();
        if($config->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'Packages not available']);
        }else{
            return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
        }
    }

    public function dataConfig($network) {
        $user = Auth::user();
        $config = DataConfig::where([['company_id', $user->company_id], ['network', $network], ['status', 1] ])->select('code','price','network','desc')->get();
        if($config->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'Packages not available']);
        }else{
            return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
        }
    }

    public function tvConfig($provider) {
        $user = Auth::user();
        $config = TvConfig::where([['company_id', $user->company_id], ['provider', $provider], ['status', 1] ])->select('code','price','provider','desc')->get();
        if($config->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'Packages not available']);
        }else{
            return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
        }
    }

    public function electricityConfig() {
        $user = Auth::user();
        $config = ElectricityConfig::where('company_id', $user->company_id)->get();
        if($config->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'Packages not available']);
        }else{
            return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
        }
    }


    public function banktransferConfig() {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer ".env('PAYSTACK_SECRET'),
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            // there was an error contacting the Paystack API
            die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $tranx['data']]);
    }


    public function ValidateBankAccount(Request $request) {
        $input = $request->all();
        $rules = array(
            'account_number'      => 'required',
            'bank_code' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=". $input['account_number'] ."&bank_code=". $input['bank_code'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "authorization: Bearer ".env('PAYSTACK_SECRET'), //replace this with your own test key
                    "content-type: application/json",
                    "cache-control: no-cache"
                ],
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if($err){
                // there was an error contacting the Paystack API
                die('Curl returned error: ' . $err);
            }

            $tranx = json_decode($response, true);

            if($tranx['status']){
                return response()->json(['status' => 1, 'message'=>'Validated successfully', 'data'=> $tranx['data']]);
            }else{
                return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
            }


        }else{
            return response()->json(['status'=> 0, 'message'=>'Unable to validate account', 'error' => $validator->errors()]);;
        }
    }

    public function ValidateUserAccount(Request $request) {
        $input = $request->all();
        $rules = array(
            'username'      => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {
            $us=User::where('phoneno', $input['username'])->orWhere('email', $input['username'])->exists();
            if($us){
                $use=User::where('phoneno', $input['username'])->orWhere('email', $input['username'])->select("last_name", "first_name")->first();
                return response()->json(['status' => 1, 'message'=>'Validated successfully', 'data'=> $use->last_name . " ".$use->first_name ]);
            }else{
                return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>'Unable to validate account', 'error' => $validator->errors()]);;
        }
    }

    public function ValidateTV(Request $request) {
        $input = $request->all();
        $rules = array(
            'iuc'      => 'required',
            'provider' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://mobilenig.com/api/bills/user_check?username=samji10&password=Emmanuel@10&service=".$input['provider']."&number=".$input['iuc'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "content-type: application/json",
                    "cache-control: no-cache"
                ],
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if($err){
                // there was an error contacting the Paystack API
                die('Curl returned error: ' . $err);
            }

            $tranx = json_decode($response, true);

            if($input['provider']=='STARTIMES'){
                if ($tranx['details']['customerName'] == null) {
                    return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
                }else{
                    return response()->json(['status' => 1, 'message'=>'Validate successfully', 'data'=> $tranx['details']['customerName']]);
                }

            }else{
                $findme   = 'accountStatus';
                $pos = strpos($response, $findme);
                // Note our use of ===.  Simply == would not work as expected
                if ($pos === false) {
                    return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
                }else{
                    return response()->json(['status' => 1, 'message'=>'Validate successfully', 'data'=> $tranx['details']['firstName'] . " " . $tranx['details']['lastName']]);
                }
            }


            /*
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://www.nellobytesystems.com/APIVerifyCableTVV1.0.asp?UserID=CK10123847&APIKey=W5352Q23GDS924D7UA1B84YYY506178I69DDE4JR1ZRAR80FCBQF819D4T7HKI85&cabletv=".$tv_type_code."&smartcardno=".$input['iuc'],
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => "GET",
                            CURLOPT_HTTPHEADER => [
                                "content-type: application/json",
                                "cache-control: no-cache"
                            ],
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);

                        if($err){
                            // there was an error contacting the Paystack API
                            die('Curl returned error: ' . $err);
                        }

                        $tranx = json_decode($response, true);

                        echo $response. "<p> </p>";

                        if($tranx['customer_name']!="INVALID_SMARTCARDNO"){
                            return response()->json(['status' => 1, 'message'=>'Validate successfully', 'data'=> $tranx['customer_name']]);
                        }else{
                            return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
                        }
            */


        }else{
            return response()->json(['status'=> 0, 'message'=>'Unable to validate account', 'error' => $validator->errors()]);;
        }
    }

    public function ValidateMeter(Request $request) {
        $input = $request->all();
        $rules = array(
            'number'      => 'required',
            'type'      => 'required',
            'provider' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {
            $type=$input['type'];

            switch ($input['provider']){
                case "PHCN":
                    $provider="PHCN";
                    $provider_code="01";
                    if($type=="postpaid"){
                        $provider="EKO_POSTPAID";
                    }else{
                        $provider="EKO_PREPAID";
                    }

                    break;

                case "IKEDC":
                    $provider="IKEDC";
                    $provider_code="02";
                    if($type=="postpaid"){
                        $provider="IKEJA";
                    }else{
                        $provider="IKEJA_TOKEN";
                    }
                    break;

                case "KEDCO":
                    $provider="KEDCO";
                    $provider_code="04";
                    if($type=="postpaid"){
                        $provider="KEDCO_POSTPAID";
                    }else{
                        $provider="KEDCO_PREPAID";
                    }

                    break;

                case "PHED":
                    $provider="PHED";
                    $provider_code="05";
                    break;

                case "JED":
                    $provider="JED";
                    $provider_code="06";
                    break;

                default:
                    $provider="JED";
                    $provider_code="06";
            }
            /* $curl = curl_init();
             curl_setopt_array($curl, array(
                 CURLOPT_URL => "https://mobilenig.com/api/bills/user_check?username=samji10&password=Emmanuel@10&service=".$provider."&number=".$input['number'],
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_HTTPHEADER => [
                     "content-type: application/json",
                     "cache-control: no-cache"
                 ],
             ));

             $response = curl_exec($curl);
             $err = curl_error($curl);

             if($err){
                 // there was an error contacting the Paystack API
                 die('Curl returned error: ' . $err);
             }

             $tranx = json_decode($response, true);

             echo $response. "<br />";

 $findme   = 'errorMessage';
 $pos = strpos($response, $findme);
 // Note our use of ===.  Simply == would not work as expected
 if ($pos === false) {
     return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
 }else{
     if ($type=="postpaid"){

     }else{

     }
     return response()->json(['status' => 1, 'message'=>'Validate successfully', 'data'=> $tranx['details']['firstName'] . " " . $tranx['details']['lastName']]);
 }*/

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.nellobytesystems.com/APIVerifyElectricityV1.asp?UserID=CK10123847&APIKey=W5352Q23GDS924D7UA1B84YYY506178I69DDE4JR1ZRAR80FCBQF819D4T7HKI85&ElectricCompany=".$provider_code."&meterno=".$input['number'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "content-type: application/json",
                    "cache-control: no-cache"
                ],
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if($err){
                // there was an error contacting the Paystack API
                die('Curl returned error: ' . $err);
            }

            $tranx = json_decode($response, true);

            $findme   = 'customer_name';
            $pos = strpos($response, $findme);
            // Note our use of ===.  Simply == would not work as expected
            if ($pos === false) {
                return response()->json(['status' => 0, 'message'=>'Could not resolve account name']);
            }else {
                if ($tranx['customer_name'] != "Invalid Meter Number.") {
                    return response()->json(['status' => 1, 'message' => 'Validate successfully', 'data' => $tranx['customer_name']]);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Could not resolve account name']);
                }
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>'Unable to validate account', 'error' => $validator->errors()]);;
        }
    }

    public function transact(Request $request) {
        $input = $request->all();
        if(auth()->user()->wallet < $input['price']){
            return response()->json(['status' => 0, 'message'=>'Your wallet balance is too low. Kindly top up']);
        }

        if ($input['type']=="airtime") {
            $config = DB::table('config_airtime')->where([['company_id', auth()->user()->company_id], 'code', $input['network']])->first();
            if ($config->status == 0){
                return response()->json(['status' => 0, 'message'=>'The service has been disabled']);

            }
        }

        if ($input['type']=="data") {
            $config = DB::table('config_airtime')->where([['company_id', auth()->user()->company_id], 'code', $input['code']])->first();
            if ($config->status == 0){
                return response()->json(['status' => 0, 'message'=>'The service has been disabled']);
            }
        }

    }
}
