<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use App\Notifications\TransactionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
{
    function banklist()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.paystack.co/bank',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . env("PAYSTACK_SECRET_KEY"),
                "Cache-Control: no-cache",
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);

        $rep = json_decode($response, true);


        return response()->json(['success' => 1, 'message' => 'Fetch successfully', 'data' => $rep['data']]);
    }

    function verifyBank(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'accountnumber' => 'required',
            'code' => 'required',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'message' => 'Incomplete request', 'error' => $validator->errors()]);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.paystack.co/bank/resolve?account_number=' . $input['accountnumber'] . '&bank_code=' . $input['code'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . env("PAYSTACK_SECRET_KEY"),
                "Cache-Control: no-cache",
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);

        $rep = json_decode($response, true);

        if ($rep['status']) {
            return response()->json(['success' => 1, 'message' => 'Fetch successfully', 'data' => $rep['data']['account_name']]);
        } else {
            return response()->json(['success' => 0, 'message' => 'Unable to resolve account details']);
        }


    }


    public function withdraw(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'wallet' => 'required',
            'amount' => 'required',
            'ref' => 'required',
            'account_number' => 'required',
            'bank' => 'required',
            'bank_code' => 'required',
        );

        $validator = Validator::make($input, $rules);

        if (!$validator->passes()) {
            return response()->json(['success' => 0, 'message' => implode(",", $validator->errors()->all())]);
        }

        $input['user_name'] = Auth::user()->user_name;

        $input['version'] = $request->header('version') ?? "2.0";

        $input['device_details'] = $request->header('device') ?? $_SERVER['HTTP_USER_AGENT'];

        $u = User::where("user_name", $input['user_name'])->first();


        if ($input['wallet'] == "Mega Bonus") {
            if ($u->bonus < $input['amount']) {
                return response()->json(['success' => 0, 'message' => 'Low wallet balance']);
            }
            $u->bonus -= $input['amount'];
        }

        if ($input['wallet'] == "Commission") {
            if ($u->agent_commision < $input['amount']) {
                return response()->json(['success' => 0, 'message' => 'Low wallet balance']);
            }
            $u->agent_commision -= $input['amount'];
        }

        $u->save();

        Withdraw::create($input);

        $u->notify(new TransactionNotification($input['amount'], 'withdrawal', 'Withdrawal request submitted'));

//        $noti = new PushNotificationController();
//        $noti->PushNoti('Izormor2019', "There is a pending withdrawal request, kindly approve on the dashboard.", "Withdrawal Request");

        return response()->json(['success' => 1, 'message' => 'Withdrawal logged successfully']);

    }



}
