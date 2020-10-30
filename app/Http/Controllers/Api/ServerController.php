<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServerController extends Controller
{

    public function buydata(Request $request){
        $rules = array(
            'plan' => 'required',
            'version' => 'required',
            'type' => 'required',
            'number' => 'required',
            'provider' => 'required',
            'user_id' => 'required');
        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {
                $input['company_id']=Auth::user()->company_id;
                $input['i_wallet']=Auth::user()->wallet;
                $input['f_wallet']='60';
                $input['date']=date('Y-m-d H:i:s');
                $input['status']="successful";
                $input['code']="debit";
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['description']=$input['provider'] .' '. $input['plan'].' - '. $input['number'];

                Transaction::create($input);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

                switch ($coded){
                    case "m500":
                        $price=175;
                        $network="MTN";
                        dataProcess($price, $network);

                        $network_code="01";
                        $dataplan="250";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "m1":
                        $price=330;
                        $network="MTN";
                        dataProcess($price, $network);
                        $price=450;
                        // dataProcess3($price, $network);

                        $network_code="01";
                        $dataplan="1000";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "m2":
                        $price=660;
                        $network="MTN";
                        dataProcess($price, $network);
                        $price=700;
                        // dataProcess3($price, $network);

                        $network_code="01";
                        $dataplan="2000";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "m5":
                        $price=1650;
                        $network="MTN";
                        dataProcess($price, $network);
                        $price=2250;
                        // dataProcess3($price, $network);

                        $network_code="01";
                        $dataplan="5000";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;


                    case "m1d":
                        $price=1650;
                        $network="MTN";
                        echo '{"success":1,"message":"pending", "network":"'.$_REQUEST['network'].'","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'", "server":"server 0"}';
                        break;

                    case "m2_5d":
                        $price=1650;
                        $network="MTN";
                        echo '{"success":1,"message":"pending", "network":"'.$_REQUEST['network'].'","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'", "server":"server 0"}';
                        break;

                    case "m6d":
                        $price=1650;
                        $network="MTN";
                        echo '{"success":1,"message":"pending", "network":"'.$_REQUEST['network'].'","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'", "server":"server 0"}';
                        break;



                    case "a1_5":
                        $price=950;
                        $network="AIRTEL";
                        // dataProcess3($price, $network);

                        $network_code="04";
                        $dataplan="1500.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;


                    case "a3_5":
                        $price=1900;
                        $network="AIRTEL";
                        // dataProcess3($price, $network);

                        $network_code="04";
                        $dataplan="3500.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "a5":
                        $price=2375;
                        $network="AIRTEL";
                        dataProcess3($price, $network);
                        break;

                    case "a7":
                        $price=3325;
                        $network="AIRTEL";
                        dataProcess3($price, $network);
                        break;


                    case "a10":
                        $price=4750;
                        $network="AIRTEL";
                        //dataProcess($price, $network);

                        $network_code="04";
                        $dataplan="10000.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "a16":
                        $price=7600;
                        $network="AIRTEL";
                        //dataProcess($price, $network);

                        $network_code="04";
                        $dataplan="16000.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "a22":
                        $price=9500;
                        $network="AIRTEL";
                        //dataProcess($price, $network);

                        $network_code="04";
                        $dataplan="22000.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;


                    case "a200d":
                        $price=1650;
                        $network="MTN";
                        echo '{"success":1,"message":"pending", "network":"'.$_REQUEST['network'].'","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'", "server":"server 0"}';
                        break;

                    case "a350d":
                        $price=1650;
                        $network="MTN";
                        echo '{"success":1,"message":"pending", "network":"'.$_REQUEST['network'].'","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'", "server":"server 0"}';
                        break;

                    case "a750d":
                        $price=1650;
                        $network="MTN";
                        echo '{"success":1,"message":"pending", "network":"'.$_REQUEST['network'].'","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'", "server":"server 0"}';
                        break;



                    case "n250":
                        $price=250;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;


                    case "n500":
                        $price=350;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;

                    case "n1":
                        $price=650;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;

                    case "n1_5":
                        $price=1000;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;

                    case "n2":
                        $price=1250;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;

                    case "n3":
                        $price=1900;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;


                    case "n4":
                        $price=2500;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;


                    case "n5":
                        $price=3100;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;



                    case "n6":
                        $price=3700;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;

                    case "n11_5":
                        $price=7000;
                        $network="9MOBILE";
                        dataProcess($price, $network);
                        //dataProcess3($price, $network);
                        break;

                    case "n15":
                        $price=8850;
                        $network="9MOBILE";
                        //dataProcess($price, $network);
                        dataProcess3($price, $network);
                        break;


                    case "n27_5":
                        $price=16000;
                        $network="9MOBILE";
                        //dataProcess($price, $network);
                        dataProcess3($price, $network);
                        break;

                    case "g1_6":
                        $price=900;
                        $network="GLO";
                        dataProcess($price, $network);

                        $network_code="02";
                        $dataplan="1600.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g3_65":
                        $price=1800;
                        $network="GLO";
                        dataProcess($price, $network);

                        $network_code="02";
                        $dataplan="3750.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g5_75":
                        $price=2250;
                        $network="GLO";
                        dataProcess($price, $network);

                        $network_code="02";
                        $dataplan="5000.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g7":
                        $price=2700;
                        $network="GLO";
                        dataProcess($price, $network);

                        $network_code="02";
                        $dataplan="6000.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g10":
                        $price=3600;
                        $network="GLO";
                        dataProcess3($price, $network);

                        $network_code="02";
                        $dataplan="8000.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g12_5":
                        $price=4500;
                        $network="GLO";
                        dataProcess($price, $network);

                        $network_code="02";
                        $dataplan="12000.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g20":
                        $price=7200;
                        $network="GLO";
                        dataProcess($price, $network);

                        $network_code="02";
                        $dataplan="16000.01";
                        // dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g26":
                        $price=8600;
                        $network="GLO";

                        $network_code="02";
                        $dataplan="1600.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g42":
                        $price=13000;
                        $network="GLO";

                        $network_code="02";
                        $dataplan="30000.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;

                    case "g62_5":
                        $price=15300;
                        $network="GLO";

                        $network_code="02";
                        $dataplan="45000.01";
                        dataProcess2($dataplan, $network_code, $network);
                        break;

                    default:
                        return '{"success":0,"message":"Invalid Order Code", "network":"n/a","number":"'.$_REQUEST['phone'].'","order_code":"'.$_REQUEST['coded'].'"}';

                }

                if(!is_numeric($amnt)){
                    // required field is missing
                    // echoing JSON response
                    return response()->json(['status'=> 0, 'message'=>'Invalid amount, retry with valid amount.']);
                }else{
                    return response()->json(['status' => 1, 'message' => 'Transaction Successful']);
                }

            }catch(\Exception $e){
                dd($e);
                return response()->json(['status'=> 0, 'message'=>'Error processing transaction','error' => $e]);
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error processing transaction', 'error' => $validator->errors()]);
        }

    }

    public function paytv(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'plan' => 'required',
            'version' => 'required',
            'type' => 'required',
            'number' => 'required',
            'provider' => 'required',
            'user_id' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {
                $input['company_id']=Auth::user()->company_id;
                $input['i_wallet']=Auth::user()->wallet;
                $input['f_wallet']='60';
                $input['date']=date('Y-m-d H:i:s');
                $input['status']="successful";
                $input['code']="debit";
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['description']=$input['provider'] .' '. $input['plan'].' - '. $input['number'];

                Transaction::create($input);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

                $api = $input['api'];
                $coded = $input['coded'];
                $phone = $input['phone'];
                $service = $input['service'];
                $user_name = $input['user_name'];
                $wallet = $input['wallet'];
                $deviceid = $input['deviceid'];
                $version = $input['version'];
                $transid = $input['transid'];

                if ($api != "mcd_app_9876234875356148750") {
                    return response()->json(['status' => 0, 'message' => 'Error, invalid request']);
                }

                switch ($coded) {
                    case "d_access":
                        $tv_type = "DSTV";
                        $tv_package = "ACSSE36";
                        $bundle_code = "ACSSW4";
                        $link = "dstv";
                        $amount = "2000";
                        $tv_type_code = "14";
                        $tv_package_code = "01";
                        $service_id = "14";
                        break;

                    case "d_family":
                        $tv_type = "DSTV";
                        $tv_package = "COFAME36";
                        $bundle_code = "COFAMW4";
                        $link = "dstv";
                        $amount = "4000";
                        $tv_type_code = "01";
                        $tv_package_code = "02";
                        $service_id = "14";
                        break;

                    case "d_compact":
                        $tv_type = "DSTV";
                        $tv_package = "COMPE36";
                        $bundle_code = "MINIBW4";
                        $link = "dstv";
                        $amount = "6800";
                        $tv_type_code = "01";
                        $tv_package_code = "03";
                        $service_id = "14";
                        break;

                    case "d_compactplus":
                        $tv_type = "DSTV";
                        $tv_package = "COMPLE36";
                        $bundle_code = "COMPLW7";
                        $link = "dstv";
                        $amount = "10650";
                        $tv_type_code = "01";
                        $tv_package_code = "04";
                        $service_id = "14";
                        break;

                    case "g_lite":
                        $tv_type = "GOTV";
                        $tv_package = "GOLITE";
                        $bundle_code = "GOLITE";
                        $link = "gotv";
                        $amount = "400";
                        $tv_type_code = "02";
                        $tv_package_code = "01";
                        $service_id = "15";
                        break;

                    case "g_jinja":
                        $tv_type = "GOTV";
                        $tv_package = "GOTVNJ1";
                        $bundle_code = "GOTVNJ1";
                        $amount = "1600";
                        $link = "gotv";
                        $tv_type_code = "02";
//                $tv_package_code="02";
                        $service_id = "15";
                        break;

                    case "g_jolli":
                        $tv_type = "GOTV";
                        $tv_package = "GOTVNJ2";
                        $bundle_code = "GOTVNJ2";
                        $amount = "2400";
                        $link = "gotv";
                        $tv_type_code = "02";
//                $tv_package_code="02";
                        $service_id = "15";
                        break;

                    case "g_value":
                        $tv_type = "GOTV";
                        $tv_package = "GOTV";
                        $bundle_code = "GOTV";
                        $amount = "1250";
                        $link = "gotv";
                        $tv_type_code = "02";
                        $tv_package_code = "02";
                        $service_id = "15";
                        break;

                    case "g_plus":
                        $tv_type = "GOTV";
                        $tv_package = "GOTVPLS";
//                    $bundle_code = "GOTVPLS";
                        $link = "gotv";
                        $amount = "1900";
                        $tv_type_code = "02";
                        $tv_package_code = "03";
                        $service_id = "15";
                        break;

                    case "g_max":
                        $tv_type = "GOTV";
                        $tv_package = "GOTVMAX";
                        $bundle_code = "GOMAX";
                        $link = "gotv";
                        $amount = "3200";
                        $tv_type_code = "02";
                        $tv_package_code = "04";
                        $service_id = "15";
                        break;


                    case "s_nova":
                        $tv_type = "STARTIMES";
                        $tv_package = "STARN";
                        $bundle_code = "900";
                        $link = "startimes";
                        $amount = "900";
                        $tv_type_code = "03";
                        $tv_package_code = "01";
                        $service_id = "16";
                        break;

                    case "s_basic":
                        $tv_type = "STARTIMES";
                        $tv_package = "STARB";
                        $bundle_code = "1300";
                        $link = "startimes";
                        $amount = "1300";
                        $tv_type_code = "03";
                        $tv_package_code = "02";
                        $service_id = "16";
                        break;

                    case "s_smart":
                        $tv_type = "STARTIMES";
                        $tv_package = "STARS";
                        $bundle_code = "1900";
                        $link = "startimes";
                        $amount = "1900";
                        $tv_type_code = "03";
                        $tv_package_code = "03";
                        $service_id = "16";
                        break;

                    case "s_classic":
                        $tv_type = "STARTIMES";
                        $tv_package = "STARC";
                        $bundle_code = "2600";
                        $link = "startimes";
                        $amount = "2600";
                        $tv_type_code = "03";
                        $tv_package_code = "04";
                        $service_id = "16";
                        break;

                    case "s_unique":
                        $tv_type = "STARTIMES";
                        $tv_package = "STARU";
                        $link = "startimes";
                        $amount = "3800";
                        $tv_type_code = "03";
                        $tv_package_code = "05";
                        $service_id = "16";
                        break;

                    default:
                        $tv_type = "";
                        // required field is missing
                        return response()->json(['status' => 0, 'message' => 'Error, Invalid coded Type. Contact info@5starcompany.com.ng for help']);
                }

                if ($tv_type == "") {
                    return response()->json(['status' => 0, 'message' => 'Error, invalid request check and try again']);
                }

                if ($tv_type == "DSTV") {
                    return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

                }

                if ($tv_type == "STARTIMES") {
                    return response()->json(['status' => 1, 'message' => 'Transaction Successful']);
                }

                if ($tv_package == "GOTVNJ1" || $tv_package == "GOTVNJ2" || $tv_package == "GOTVMAX") {
                    return response()->json(['status' => 1, 'message' => 'Transaction Successful']);
                } else {
//                $this->paytvProcess($amount, $tv_package, $link, $tv_type, $coded, $phone);
                    return response()->json(['success' => 1, 'message' => "pending", 'service'=> $tv_type, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 0"]);

                }

            }catch(\Exception $e){
                dd($e);
                return response()->json(['status'=> 0, 'message'=>'Error processing transaction','error' => $e]);
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error processing transaction', 'error' => $validator->errors()]);
        }

    }



    public function buyairtime(Request $request){
        $input = $request->all();
        $rules = array(
            'type' => 'required',
            'number' => 'required',
            'provider' => 'required',
            'amount' => 'required',
            'version' => 'required',
            'user_id' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {

                $input['company_id']=Auth::user()->company_id;
                $input['i_wallet']=Auth::user()->wallet;
                $input['f_wallet']='60';
                $input['date']=date('Y-m-d H:i:s');
                $input['status']="successful";
                $input['code']="debit";
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['description']=$input['provider'] .' '. $input['amount'].' - '. $input['number'];

                Transaction::create($input);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

                switch ($coded){
                    case "m":
                        $network="MTN";
                        $network_code="01";
                        $service_id="7";
                        break;

                    case "M":
                        $network="MTN";
                        $network_code="01";
                        $service_id="7";
                        break;

                    case "e":
                        $network="9MOBILE";
                        $network_code="03";
                        $service_id="9";
                        break;

                    case "E":
                        $network="9MOBILE";
                        $network_code="03";
                        $service_id="9";
                        break;

                    case "9":
                        $network="9MOBILE";
                        $network_code="03";
                        $service_id="9";
                        break;

                    case "g":
                        $network="GLO";
                        $network_code="02";
                        $service_id="8";
                        break;

                    case "G":
                        $network="GLO";
                        $network_code="02";
                        $service_id="8";
                        break;

                    case "a":
                        $network="AIRTEL";
                        $network_code="04";
                        $service_id="6";
                        break;

                    case "A":
                        $network="AIRTEL";
                        $network_code="04";
                        $service_id="6";
                        break;

                    default:
                        $network="";
                        // required field is missing
                        return response()->json(['status'=> 0, 'message'=>'Invalid Network. Available are m for MTN, 9 for 9MOBILE, g for GLO, a for AIRTEL.']);
                }

                if(!is_numeric($amnt)){
                    // required field is missing
                    // echoing JSON response
                    return response()->json(['status'=> 0, 'message'=>'Invalid amount, retry with valid amount.']);
                }else{
                    return response()->json(['status' => 1, 'message' => 'Transaction Successful']);
                }

            }catch(\Exception $e){
                dd($e);
                return response()->json(['status'=> 0, 'message'=>'Error processing transaction','error' => $e]);
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error processing transaction', 'error' => $validator->errors()]);
        }

    }


    public function buyelectricity(Request $request) {
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

            return response()->json(['status' => 1, 'message' => 'Transaction successfully']);

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

    public function buytransfer(Request $request) {
        $input = $request->all();
        $rules = array(
            'username'      => 'required',
            'type'      => 'required',
            'amount'      => 'required',
            'account_name'      => 'required',
            'provider' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {
            $input['company_id']=Auth::user()->company_id;
            $input['i_wallet']=Auth::user()->wallet;
            $input['f_wallet']='60';
            $input['date']=date('Y-m-d H:i:s');
            $input['status']="successful";
            $input['code']="debit";
            $input['ip_address']=$_SERVER['REMOTE_ADDR'];
            $input['description']=$input['provider'] .' '. $input['amount'].' - '. $input['username'].' =>'. $input['account_name'];

            Transaction::create($input);

            return response()->json(['status' => 1, 'message' => 'Transaction successfully']);

        }else{
            return response()->json(['status'=> 0, 'message'=>'Unable to validate account', 'error' => $validator->errors()]);;
        }
    }


    public function paytvProcess4($service_id, $phone, $bundle_code, $amount, $coded, $tv_type)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.myflex.ng/users/account/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"phone\": \"+2348166939205\",\n  \"password\": \"Emmanuel@10\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $token = $response['token'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.myflex.ng/services/category/" . $service_id . "/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"account\": \"" . $phone . "\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $token,
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $name = $response['data']['name'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.myflex.ng/bills/pay/tv",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n\t\"service_category_id\": \"" . $service_id . "\",\n\t\"smartcard\": \"" . $phone . "\",\n\t\"bundleCode\": \"" . $bundle_code . "\",\n\t\"amount\": \"" . $amount . "\",\n\t\"name\": \"" . $name . "\",\n\t\"invoicePeriod\": \"1\",\n\t\"phone\": \"08000000000\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: " . $token,
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $status = $response['status'];

        if ($status == "success") {
            $tran_stat = "1";
            $tran_msg = "Package " . $coded . " Delivered on " . $phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'service'=> $tv_type, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 4"]);
        } else {
            $tran_stat = "0";
            $tran_msg = "Unsuccessful Order " . $coded . " for " . $phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'service'=> $tv_type, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 4"]);
        }
    }

    function paytvProcess($amnt, $tv_package, $link, $tv_type, $coded, $phone){
        $ref=date('ymdhis');
//start of checking
        $url="https://mobilenig.com/api/bills/user_check?username=samji10&password=Emmanuel@10&service=".$tv_type."&number=".$phone;
        // Perform initialize to validate name on server

        $result = file_get_contents($url);
        echo $result;
        $findme   = 'accountStatus';
        $pos = strpos($result, $findme);
        $arr = json_decode($result, true);
        // Note our use of ===.  Simply == would not work as expected
        if ($pos === false) {
            $findme   = 'billAmount';
            $pos = strpos($result, $findme);

            if ($pos === false) {
                $GLOBALS['success'] = 0;
                $response["message"] = "The device number supplied did not return any data.";
            }else{
                if($arr["details"]["returnCode"]==0){
                    // Print a single value
                    $GLOBALS['success'] = 1;
                    $GLOBALS['customer_name'] ="samji";
                    $GLOBALS['customer_number'] = $arr["details"]["customerNumber"];
                }else{
                    $GLOBALS['success'] = 0;
                    $response["message"] = "The device number supplied did not return any data.";
                }
            }
        } else {
            // Print a single value
            $GLOBALS['success'] = 1;
            $GLOBALS['customer_name'] = "samji";
            $GLOBALS['customer_number'] = $arr["details"]["customerNumber"];
        }

//begining of buying
        if($GLOBALS['success'] ==1){
            $url="https://mobilenig.com/api/bills/".$link."?username=samji10&password=Emmanuel@10&smartno=".$phone."&product_code=".$tv_package."&customer_name=".$GLOBALS['customer_name']."&customer_number=".$GLOBALS['customer_number']."&ref=".$ref."&amount=".$amnt;

            $result = file_get_contents($url);

            if ($result == "00") {
                $tran_stat="1";
                $tran_msg="Package ".$coded." Delivered on ".$phone;

                echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'service'=> $tv_type, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 1"]);
            }else {

                $tran_stat="0";
                $tran_msg="Unsuccessful Order ".$coded." for ".$phone;

                echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'service'=> $tv_type, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 1"]);
            }
        }else{
            $tran_stat="0";
            $tran_msg="Unsuccessful Order ".$coded." for ".$phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'service'=> $tv_type, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 4"]);


//            echo '{"success":'.$tran_stat.',"message":"'.$tran_msg.'", "service":"'.$tv_type.'","number":"'.$phone.'","order_code":"'.$coded.'", "server":"server 1"}';
        }
    }

    public function airtimeProcess($amnt, $network, $coded, $phone){
        $ref=date('ymdhis');

        $url="https://mobilenig.com/api/airtime.php/?user_name=samji10&password=Emmanuel@10&network=".$network."&phoneNumber=".$phone."&amount=".$amnt;

        $result = file_get_contents($url);

        if ($result == "00") {
            $tran_stat="1";
            $tran_msg=$network." Airtime ".$amnt." Delivered on ".$phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 1"]);

        }else {

            $tran_stat="0";
            $tran_msg="Unsuccessful ".$network." Airtime ".$amnt." for ".$phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 1"]);
        }
    }

    public function airtimeProcess2($amnt, $network_code, $network, $phone, $coded){
//01 for MTN, 02 for Glo, 03 for Etisalat, 04 for Airtel

        $url="https://www.nellobytesystems.com/APIAirtimeV1.asp?UserID=CK10123847&APIKey=W5352Q23GDS924D7UA1B84YYY506178I69DDE4JR1ZRAR80FCBQF819D4T7HKI85&MobileNetwork=".$network_code."&Amount=".$amnt."&MobileNumber=".$phone."&CallBackURL=https://www.5starcompany.com.ng";

        // Perform transaction/initialize on our server to buy

        $result = file_get_contents($url);

        // Convert JSON string to Array
        $someArray = json_decode($result, true);
        // Dump all data of the Array
        $result=$someArray["status"]; // Access Array data

        if ($result == "ORDER_RECEIVED" || $result == "ORDER_COMPLETED") {
            $tran_stat="1";
            $tran_msg="Data ".$coded." Delivered on ".$phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 2"]);

        }else if ($result == "INVALID_RECIPIENT") {
            $tran_stat="0";
            $tran_msg="An invalid mobile phone number was entered (".$phone. ")";

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 2"]);
        }else {

            $tran_stat="0";
            $tran_msg="Unsuccessful Order ".$coded." for ".$phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 2"]);
        }
    } //ending function

    public function airtimeProcess3($amnt, $network, $coded, $phone)
    {
        $url = "https://minitechs.com.ng/api/vtu.php?username=08166939205&password=Emmanuel@10&network=" . $network . "&number=" . $phone . "&amount=" . $amnt;

        $result = file_get_contents($url);
        if ($result == "00") {
            $tran_stat = "1";
            $tran_msg = $network . " Airtime " . $amnt . " Delivered on " . $phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 3"]);
        } else {

            $tran_stat = "0";
            $tran_msg = "Unsuccessful " . $network . " Airtime " . $amnt . " for " . $phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> "server 3"]);
        }
    }



    public function airtimeProcess4($amnt, $service_id, $phone, $network, $coded)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.myflex.ng/users/account/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"phone\": \"+2348166939205\",\n  \"password\": \"Emmanuel@10\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $token = $response['token'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.myflex.ng/bills/pay/airtime",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"amount\": \"".$amnt."\",\n  \"service_category_id\": \"" . $service_id . "\",\n  \"phonenumber\": \"" . $phone . "\",\n  \"status_url\": \"https://admin-mcd.5starcompany.com.ng/api/hook\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $token,
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $status = $response['status'];

        if($status == "success"){
            $tran_stat = 1;
            $tran_msg = $network . " Airtime " . $amnt . " Delivered on " . $phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> 'server 4']);
        }else {

            $tran_stat = 0;
            $tran_msg = "Unsuccessful " . $network . " Airtime " . $amnt . " for " . $phone;

            echo json_encode(['success' => $tran_stat, 'message' => $tran_msg, 'network'=> $network, 'number'=> $phone, 'order_code'=> $coded, 'server'=> 'server 4']);
        }

    }

}
