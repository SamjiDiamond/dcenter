<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\A2CConfig;
use App\Models\AirtimeConfig;
use App\Models\DataConfig;
use App\Models\ElectricityConfig;
use App\Models\Transaction;
use App\Models\TvConfig;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServerController extends Controller
{

    public function buydata(Request $request)
    {
        $rules = array(
            'version' => 'required',
            'code' => 'required',
            'number' => 'required');
        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {

                $dc = DataConfig::where('identifier', $input['code'])->first();
                if (!$dc) {
                    return response()->json(['status' => 0, 'message' => 'Invalid Code supplied']);
                }

//                if ($dc->company_id != Auth::user()->company_id) {
//                    return response()->json(['status' => 0, 'message' => 'A fatai error occur. Kindly contact the server admin']);
//                }

                if ($dc->price > Auth::user()->wallet) {
                    return response()->json(['status' => 0, 'message' => 'Insufficient balance. Kindly topup']);
                }

                $input['reference_id'] = Auth::user()->company_id . "d" . date('ymd') . rand();
                $input['company_id'] = Auth::user()->company_id;
                $input['user_id'] = Auth::id();
                $input['amount'] = $dc->price;
                $input['i_wallet'] = Auth::user()->wallet;
                $input['f_wallet'] = Auth::user()->wallet - $dc->price;
                $input['date'] = Carbon::now();
                $input['status'] = "successful";
                $input['code'] = $dc->code;
                $input['type'] = "debit";
                $input['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $input['description'] = $dc->network . ' Data ' . $dc->code . ' - ' . $input['number'];

                Transaction::create($input);

                User::where('id', Auth::id())->update(['wallet' => $input['f_wallet']]);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

            }catch(\Exception $e){
//                dd($e);
                return response()->json(['status'=> 0, 'message'=>'Error processing transaction','error' => $e]);
            }

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error processing transaction', 'error' => $validator->errors()]);
        }

    }

    public function buyairtime(Request $request)
    {
        $rules = array(
            'version' => 'required',
            'code' => 'required',
            'amount' => 'required|int|min:50',
            'number' => 'required');
        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {
            try {
                $dc = AirtimeConfig::where('identifier', $input['code'])->first();
                if (!$dc) {
                    return response()->json(['status' => 0, 'message' => 'Invalid Code supplied']);
                }

//                if ($dc->company_id != Auth::user()->company_id) {
//                    return response()->json(['status' => 0, 'message' => 'A fatai error occur. Kindly contact the server admin']);
//                }

                $amount = $input['amount'];
                $d = ($dc->price * $input['amount']) / 100;
                $price = round($input['amount'] - $d);

                if ($price > Auth::user()->wallet) {
                    return response()->json(['status' => 0, 'message' => 'Insufficient balance. Kindly topup']);
                }

                $input['reference_id'] = Auth::user()->company_id . "d" . date('ymd') . rand();
                $input['company_id'] = Auth::user()->company_id;
                $input['user_id'] = Auth::id();
                $input['amount'] = $price;
                $input['i_wallet'] = Auth::user()->wallet;
                $input['f_wallet'] = Auth::user()->wallet - $dc->price;
                $input['date'] = Carbon::now();
                $input['status'] = "successful";
                $input['code'] = $dc->code;
                $input['type'] = "debit";
                $input['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $input['description'] = $dc->code . ' Airtime ' . $amount . ' - ' . $input['number'];

                Transaction::create($input);

                User::where('id', Auth::id())->update(['wallet' => $input['f_wallet']]);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

            } catch (\Exception $e) {
                return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $e]);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $validator->errors()]);
        }
    }

    public function paytv(Request $request)
    {
        $rules = array(
            'version' => 'required',
            'code' => 'required',
            'number' => 'required');
        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {

                $dc = TvConfig::where('identifier', $input['code'])->first();
                if (!$dc) {
                    return response()->json(['status' => 0, 'message' => 'Invalid Code supplied']);
                }

//                if ($dc->company_id != Auth::user()->company_id) {
//                    return response()->json(['status' => 0, 'message' => 'A fatai error occur. Kindly contact the server admin']);
//                }

                if ($dc->price > Auth::user()->wallet) {
                    return response()->json(['status' => 0, 'message' => 'Insufficient balance. Kindly topup']);
                }

                $input['reference_id'] = Auth::user()->company_id . "d" . date('ymd') . rand();
                $input['company_id'] = Auth::user()->company_id;
                $input['user_id'] = Auth::id();
                $input['amount'] = $dc->price;
                $input['i_wallet'] = Auth::user()->wallet;
                $input['f_wallet'] = Auth::user()->wallet - $dc->price;
                $input['date'] = Carbon::now();
                $input['status'] = "successful";
                $input['code'] = $dc->code;
                $input['type'] = "debit";
                $input['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $input['description'] = $dc->desc . ' - ' . $input['number'];

                Transaction::create($input);

                User::where('id', Auth::id())->update(['wallet' => $input['f_wallet']]);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

            } catch (\Exception $e) {
                dd($e);
                return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $e]);
            }

        } else {
            return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $validator->errors()]);
        }

    }

    public function a2c(Request $request)
    {
        $rules = array(
            'version' => 'required',
            'dest' => 'required',
            'amount' => 'required',
            'number' => 'required',);
        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {

                $dc = A2CConfig::where([['company_id', Auth::user()->company_id], ['code', $input['dest']]])->first();
                if (!$dc) {
                    return response()->json(['status' => 0, 'message' => 'Invalid Request. Current Company do not support your request']);
                }

                if ($dc->company_id != Auth::user()->company_id) {
                    return response()->json(['status' => 0, 'message' => 'A fatai error occur. Kindly contact the server admin']);
                }

                $amount=$input['amount'];
                $p=$dc->price/100;
                $pri=$amount*$p;
                $price=$amount-$pri;

                $input['reference_id'] = Auth::user()->company_id . "d" . date('ymd') . rand();
                $input['company_id'] = Auth::user()->company_id;
                $input['user_id'] = Auth::id();
                $input['amount'] = $price;
                $input['i_wallet'] = Auth::user()->wallet;
                $input['f_wallet'] = Auth::user()->wallet + $price;
                $input['date'] = Carbon::now();
                $input['status'] = "pending";
                $input['code'] = $dc->code;
                $input['type'] = "credit";
                $input['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $input['description'] = ' A2C - ' .$input['dest']." -  #" . $amount. " " . $input['number'];
                if($input['dest']=="bank"){
                    $input['description'] .= " " . $input['bank'];
                }
                $input['extra'] = $amount;

                Transaction::create($input);

//                User::where('id', Auth::id())->update(['wallet' => $input['f_wallet']]);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

            } catch (\Exception $e) {
                dd($e);
                return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $e]);
            }

        } else {
            return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $validator->errors()]);
        }

    }

    public function buyelectricity(Request $request)
    {
        $rules = array(
            'version' => 'required',
            'code' => 'required',
            'amount' => 'required',
            'number' => 'required');
        $input = $request->all();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            try {

                $dc = ElectricityConfig::where('identifier', $input['code'])->first();
                if (!$dc) {
                    return response()->json(['status' => 0, 'message' => 'Invalid Code supplied']);
                }

                if ($dc->company_id != Auth::user()->company_id) {
                    return response()->json(['status' => 0, 'message' => 'A fatai error occur. Kindly contact the server admin']);
                }

                if ($dc->price > Auth::user()->wallet) {
                    return response()->json(['status' => 0, 'message' => 'Insufficient balance. Kindly topup']);
                }

                $amount=$input['amount'];
                $p=$dc->price/100;
                $pri=$amount*$p;
                $price=$amount-$pri;

                $input['reference_id'] = Auth::user()->company_id . "d" . date('ymd') . rand();
                $input['company_id'] = Auth::user()->company_id;
                $input['user_id'] = Auth::id();
                $input['amount'] = $price;
                $input['i_wallet'] = Auth::user()->wallet;
                $input['f_wallet'] = Auth::user()->wallet - $price;
                $input['date'] = Carbon::now();
                $input['status'] = "successful";
                $input['code'] = $dc->code;
                $input['type'] = "debit";
                $input['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $input['description'] = $dc->desc . ' - #' . $amount . " " . $input['number'];

                Transaction::create($input);

                User::where('id', Auth::id())->update(['wallet' => $input['f_wallet']]);

                return response()->json(['status' => 1, 'message' => 'Transaction Successful']);

            } catch (\Exception $e) {
                dd($e);
                return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $e]);
            }

        } else {
            return response()->json(['status' => 0, 'message' => 'Error processing transaction', 'error' => $validator->errors()]);
        }

    }


    public function buyelectricit(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'number' => 'required',
            'type' => 'required',
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
