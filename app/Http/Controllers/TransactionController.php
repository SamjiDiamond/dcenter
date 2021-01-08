<?php

namespace App\Http\Controllers;

use App\Jobs\FundwalletJob;
use App\Model\Company;
use App\Model\SmsPayment;
use App\Model\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function fund_wallet(Request $request){

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
//            try {
                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
                if(!$user){
                    return redirect('fundwallet')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if($user->company_id != auth()->user()->company_id){
                    return redirect('fundwallet')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $input['company_id']=$user->company_id;
                $input['user_id']=$user->id;
                $input['i_wallet']=$user->wallet;
                $input['f_wallet']=$user->wallet + $input['amount'];
                $input['name']="Wallet";
                $input['status']="successful";
                $input['date']=Carbon::now();
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['device']=$_SERVER['HTTP_USER_AGENT'];
                $input['extra']="Wallet funded by ". Auth::user()->email;
                $input['code']="fund_wallet";
                $input['type']="fund_wallet";
                $input['description']="wallet funded successfully ".$input['odescription'];

                Transaction::create($input);

                $user->wallet += $input['amount'];
                $user->save();

                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
                dispatch($emailJob);


                return redirect('fundwallet')->with('success', $user->first_name . ' wallet funded successfully');
//            }catch(\Exception $e){
//                DB::rollback();
//                return redirect('fundwallet')->with('error','Error funding wallet');
//            }
        }else{
            DB::rollback();
            return redirect('fundwallet')->with('error','Error funding wallet, check your input and try again');
        }

    }

    public function charge_customer(Request $request){

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
            'description' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
                if(!$user){
                    return redirect('chargecustomer')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if($user->company_id != auth()->user()->company_id){
                    return redirect('chargecustomer')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $input['company_id']=$user->company_id;
                $input['user_id']=$user->id;
                $input['i_wallet']=$user->wallet;
                $input['f_wallet']=$user->wallet - $input['amount'];
                $input['name']="Wallet Charges";
                $input['status']="successful";
                $input['date']=Carbon::now();
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['device']=$_SERVER['HTTP_USER_AGENT'];
                $input['extra']="Wallet charged by ". Auth::user()->email;
                $input['code']="charge_customer";
                $input['type']="charge_customer";

                Transaction::create($input);

                $user->wallet -= $input['amount'];
                $user->save();

//                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
//                dispatch($emailJob);


                return redirect('chargecustomer')->with('success', $user->first_name . ' wallet charged successfully');
            }catch(\Exception $e){
                DB::rollback();
                return redirect('chargecustomer')->with('error','Error charging customer');
            }
        }else{
            DB::rollback();
            return redirect('chargecustomer')->with('error','Error charging customer, check your input and try again');
        }

    }

    public function post_airtime_transaction(Request $request){

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
            'phoneno' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
                if(!$user){
                    return redirect('postairtimetransaction')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if($user->company_id != auth()->user()->company_id){
                    return redirect('postairtimetransaction')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $input['company_id']=$user->company_id;
                $input['user_id']=$user->id;
                $input['i_wallet']=$user->wallet;
                $input['f_wallet']=$user->wallet - $input['amount'];
                $input['name']="Transaction";
                $input['status']="successful";
                $input['date']=Carbon::now();
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['device']=$_SERVER['HTTP_USER_AGENT'];
                $input['extra']="Transaction posted by ". Auth::user()->email;
                $input['code']="post_transaction";
                $input['type']="airtime";
                $input['description']="airtime " . $input['amount'] . " on ".$input['phoneno'];

                Transaction::create($input);

                $user->wallet -= $input['amount'];
                $user->save();

//                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
//                dispatch($emailJob);


                return redirect('postairtimetransaction')->with('success', $user->first_name . ' transaction posted successfully');
            }catch(\Exception $e){
                DB::rollback();
                return redirect('postairtimetransaction')->with('error','Error posting transaction');
            }
        }else{
            DB::rollback();
            return redirect('postairtimetransaction')->with('error','Error posting , check your input and try again');
        }

    }
    public function recharge_card(Request $request){

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
            'network' => 'required',
            'quantity' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
                if(!$user){
                    return redirect('rechargecard')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $user=User::where("email","=",$input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if($user->company_id != auth()->user()->company_id){
                    return redirect('rechargecard')->with(['error' => 'Email or Phone number does not exist.']);
                }

                $input['description']=$input['network'] . " rechargecard " . $input['amount'] . " of ".$input['quantity'] ." quantity";
                $amount=$input['amount'] * $input['quantity'];
                $input['amount']=$amount;
                $input['company_id']=$user->company_id;
                $input['user_id']=$user->id;
                $input['i_wallet']=$user->wallet;
                $input['f_wallet']=$user->wallet - $amount;
                $input['name']="Recharge Card";
                $input['status']="successful";
                $input['date']=Carbon::now();
                $input['ip_address']=$_SERVER['REMOTE_ADDR'];
                $input['device']=$_SERVER['HTTP_USER_AGENT'];
                $input['extra']="Transaction posted by ". Auth::user()->email;
                $input['code']="recharge_card";
                $input['type']=$input['network'];

                Transaction::create($input);

                $user->wallet -= $amount;
                $user->save();

//                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
//                dispatch($emailJob);

                return redirect('rechargecard')->with('success', $user->first_name . ' recharge card sent successfully');
            }catch(\Exception $e){
                DB::rollback();
                return redirect('rechargecard')->with('error','Error sending recharge card');
            }
        }else{
            DB::rollback();
            return redirect('rechargecard')->with('error','Error computing recharge card , check your input and try again');
        }

    }

    public function reversal(Request $request){

        $input = $request->all();
        $rules = array(
            'id' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $tran=Transaction::where("id","=",$input['id'])->exists();
                if(!$tran){
                    return redirect('reversal')->with(['error' => 'Transaction ID does not exist.']);
                }

                $tran=Transaction::join('users','users.id','=','transactions.user_id')
                    ->select('transactions.*','users.first_name','users.last_name')
                    ->where('transactions.id','=',$input['id'])
                    ->first();

                if($tran->company_id != auth()->user()->company_id){
                    return redirect('reversal')->with(['error' => 'Transaction ID does not exist.']);
                }

                if($tran->name == 'Reversal'){
                    return redirect('reversal')->with(['error' => 'Reversed Transaction can not be reversed']);
                }

                $rtran=Transaction::where([['description','LIKE', '%'.$tran->description.'%'], ['name', '=', 'Reversal']])->exists();

                if($rtran){
                    return redirect('reversal')->with(['error' => 'Transaction can only be reversed once']);
                }

                return view('reversal', ['data' =>$tran, 't'=>true]);

            }catch(\Exception $e){
                DB::rollback();
                return redirect('reversal')->with('error','Error fetching transaction details');
            }
        }else{
            DB::rollback();
            return redirect('reversal')->with('error','Error fetching transaction details , check your input and try again');
        }

    }


    public function reversalpost(Request $request){

        $input = $request->all();
        $rules = array(
            'id' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $tran=Transaction::where("id","=",$input['id'])->exists();
                if(!$tran){
                    return redirect('reversal')->with(['error' => 'Transaction ID does not exist.']);
                }

                $tran=Transaction::join('users','users.id','=','transactions.user_id')
                    ->select('transactions.*','users.first_name','users.last_name')
                    ->where('transactions.id','=',$input['id'])
                    ->first();

                if($tran->company_id != auth()->user()->company_id){
                    return redirect('reversal')->with(['error' => 'Transaction ID does not exist.']);
                }

                if($tran->name == 'Reversal'){
                    return redirect('reversal')->with(['error' => 'Reversed Transaction can not be reversed']);
                }

                $rtran=Transaction::where([['description','LIKE', '%'.$tran->description.'%'], ['name', '=', 'Reversal']])->exists();

                if($rtran){
                    return redirect('reversal')->with(['error' => 'Transaction can only be reversed once']);
                }

                return redirect('reversal')->with('success','Reversal posted successfully');

            }catch(\Exception $e){
                DB::rollback();
                return redirect('reversal')->with('error','Error fetching transaction details');
            }
        }else{
            DB::rollback();
            return redirect('reversal')->with('error','Error fetching transaction details , check your input and try again');
        }

    }

    public function sendEmail()
    {
        $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(3));
        dispatch($emailJob);

        echo 'email sent';
    }


}
