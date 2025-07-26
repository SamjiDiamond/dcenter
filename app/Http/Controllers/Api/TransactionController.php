<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AirtimeConfig;
use App\Models\DataConfig;
use App\Models\ElectricityConfig;
use App\Models\Transaction;
use App\Models\TvConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $config = AirtimeConfig::where([['company_id', $user->company_id], ['status', 1]])->select('identifier', 'code', 'price')->get();
        if($config->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'Packages not available']);
        }else{
            return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
        }
    }

    public function dataConfig($network) {
        $user = Auth::user();
        $config = DataConfig::where([['company_id', $user->company_id], ['network', $network], ['status', 1]])->select('identifier', 'price', 'network', 'desc')->get();
        if($config->isEmpty()){
            return response()->json(['status' => 0, 'message'=>'Packages not available']);
        }else{
            return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
        }
    }

    public function tvConfig($provider) {
        $user = Auth::user();
        $config = TvConfig::where([['company_id', $user->company_id], ['provider', $provider], ['status', 1]])->select('identifier', 'price', 'provider', 'desc')->get();
        if($config->isEmpty()){
            $config = TvConfig::where([['company_id', 0], ['provider', $provider], ['status', 1]])->select('identifier', 'price', 'provider', 'desc')->get();
            if($config->isEmpty()){
                return response()->json(['status' => 0, 'message'=>'Packages not available']);
            }else{
                return response()->json(['status' => 1, 'message'=>'loaded successfully', 'data'=> $config]);
            }
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
                CURLOPT_SSL_VERIFYPEER => false,
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
                CURLOPT_URL => env('MCD_URL').'/validate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => '{
    "service": "tv",
    "provider": "'.$input['provider'].'",
    "number": "'.$input['iuc'].'"
}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.env('MCD_TOKEN')
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $rep=json_decode($response, true);


            Log::info('billersCode:'. $input['iuc'].'serviceID:'. $input['provider']."==Validate TV Name ==".$response);

            try{
                if($rep['success'] == 1) {
                    return response()->json(['status' => 1, 'message' => 'Validated successfully', 'data' => $rep['data'], 'details' => $rep['details']]);
                }else{
                    return response()->json([
                        'status' => 0,
                        'message' => $rep['message']['error']
                    ]);
                }
            }catch (\Exception $e){
                Log::error('billersCode:'. $input['iuc'].'serviceID:'. $input['provider']."==Validate TV Name ==".$response,[$e]);
                return response()->json([
                    'status' => 0,
                    'message' => 'Could not resolve account name'
                ]);
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>implode(",",$validator->errors()->all()), 'error' => $validator->errors()]);
        }
    }

    public function ValidateMeter(Request $request) {
        $input = $request->all();
        $rules = array(
            'number'      => 'required',
            'type'      => 'required',
            'identifier' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {
            $type=$input['type'];

            $provider=ElectricityConfig::where('identifier',$input['identifier'])->first();

            if(!$provider){
                return response()->json(['status' => 0, 'message' => 'Invalid Identifier supplied']);
            }


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('MCD_URL').'/validate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => '{
    "service": "electricity",
    "provider": "'.$provider->code.'",
    "number": "'.$input['number'].'"
}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.env('MCD_TOKEN')
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $rep=json_decode($response, true);


            Log::info('number:'. $input['number'].'serviceID:'. $input['identifier']."==Validate Meter Name ==".$response);

            try{
                if($rep['success'] == 1) {
                    return response()->json(['status' => 1, 'message' => 'Validated successfully', 'data' => $rep['data'], 'details' => $rep['details']]);
                }else{
                    return response()->json([
                        'status' => 0,
                        'message' => $rep['message']['error']
                    ]);
                }
            }catch (\Exception $e){
                Log::error('number:'. $input['number'].'serviceID:'. $input['identifier']."==Validate Meter Name ==".$response,[$e]);
                return response()->json([
                    'status' => 0,
                    'message' => 'Could not resolve account name'
                ]);
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
