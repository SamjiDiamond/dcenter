<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Payout;
use App\Http\Requests\KorapayWebhookRequest;

class KorapayWebhookController extends Controller{

 
    public function webhookUrl(KorapayWebhookRequest $request){


        $hash = hash_hmac(
            "sha256",
            json_encode($request->data),
            env('KORAPAY_SECRET'),
            $binary = false
        );

       if($request->headers->get('x-korapay-signature') === $hash){
            if($request->data['status'] == 'success'){
                $payout =  Payout::where('account_reference', $request->data['reference'])->first();
                    if($payout){
                        $payout->status = "success";
                        $payout->save();
                        return response()->json(['success' => 'success'],200);
                    }
            }
        }
         else{
                response()->json(['error' => 'invalid'], 401);
        }
    }
}

