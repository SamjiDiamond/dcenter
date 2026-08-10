<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Notifications\TransactionNotification;
use Illuminate\Http\Request;


class PayoutController extends Controller
{
    private $reference;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.korapay.com/merchant/api/v1/misc/banks',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer pk_test_vkibYSxUTwWRBUBcdQTdhmsqf18VCR3H9oN7dCU6',
                'Cookie: __cf_bm=5m0MmNvrOGO8RiDaMZwYZ2gUWbDkjFMjLgm3bmB4bHo-1650304404-0-Aa886tZjaJ5hJlzXa6bbY/M6Ox9Uki7CF1KAQzFX9Dtbt+ozc2wJlC+IOsQYtXWt2+SBmkt8NX82SWgMSUspUE4='
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $response = json_decode($response);

            $banks = $response->data;


        return view('payout.payoutform', compact('banks'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request,[
            'account_number' => 'required',
            'bank_code' => 'required',
            'amount' => 'required',
        ]);


        $ref = substr(md5(time()), 0, 10);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.korapay.com/merchant/api/v1/transactions/disburse',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "reference": "' . $ref . '",
            "destination": {
                "type": "bank_account",
                "amount": "'.$request->amount .'",
                "currency": "NGN",
                "narration": "Test Transfer Payment",
                "bank_account": {
                    "bank": "'.$request->bank_code.'",
                    "account": "'.$request->account_number.'"
                },
                "customer": {
                    "name": "'. auth()->user()->firstname ." " . auth()->user()->lastname . '",
                    "email": "'. auth()->user()->email  . '"
                }
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer sk_test_9YusxDq7qXi2sksYvQENTCCCQVDpoujZpRbVMbUG',
            'Cookie: __cf_bm=m1T6UW2THul5Lzf4vle_PfUMQX7TlOilwAroveVLUo8-1650294938-0-AbVNHnWecl0/xSgX1/zARL5xuD57YfOS3dCDw8BC1ys2yCPUu7J4G8Vtrt7OH0FgwdwUIST7oAymgz54ChffFjM='
        ),
        ));

        $response = curl_exec($curl);
            
            curl_close($curl);
            //dd($response);
            
            $rep=json_decode($response, true);
            if($rep['status']== true){
               
                $payout = new Payout();
                $payout->account_name = auth()->user()->first_name ." " . auth()->user()->last_name;
                $payout->amount = $rep['data']['amount'];
                $payout->account_reference =  $rep['data']['reference'];
                $payout->status = $rep['data']['status'];
                $payout->bank_code = $request->bank_code;
                
                $payout->save();   

                auth()->user()->notify(new TransactionNotification($rep['data']['amount'] ?? $request->amount, 'payout', 'Payout completed successfully'));
                
                return redirect()->back()->withToast('Payout Successful!');
            }else{
                return redirect()->back()->withToast('Payout Failed!', 'danger');
            }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payout  $payout
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function edit(Payout $payout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payout $payout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payout $payout)
    {
        //
    }
}
