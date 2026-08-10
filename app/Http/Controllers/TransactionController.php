<?php

namespace App\Http\Controllers;

use App\Jobs\FundwalletJob;
use App\Models\CompanyWallet;
use App\Models\ServiceCharge;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\TransactionNotification;
use App\Notifications\WalletFundingNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('company', 'user')->where('user_id', auth()->user()->id)->get();
        $sn = 1;
        return view('transaction_list', compact('sn', 'transactions'));
    }



    public function accountLedger()
    {

        if (request()->filled('customerId') && request()->filled('transactionId')) {
            $transactions = Transaction::where('user_id', request()->query('customerId'))->where('reference_id', request()->filled('transactionId'))->get();
            return view('report_account_ledger', compact('transactions'));
        }

        return view('report_account_ledger');
    }



    public function fetchTransaction()
    {
        $authenticatedUser = auth()->user();
        $companyWallets = CompanyWallet::with('company')->where('company_id', $authenticatedUser->company_id)->get();
        $i = 1;
        return view('report_company_wallet_ledger', compact('i', 'companyWallets'));
    }




    public function fund_wallet(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.'
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            //            try {
            $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
            if (!$user) {
                return redirect('fundwallet')->withToast('Email or Phone number does not exist.', 'danger');
            }

            $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

            if ($user->company_id != auth()->user()->company_id) {
                return redirect('fundwallet')->withToast('Email or Phone number does not exist.', 'danger');
            }

            $input['reference_id'] = Auth::user()->company_id . "c" . date('ymd') . rand();
            $input['company_id'] = $user->company_id;
            $input['user_id'] = $user->id;
            $input['i_wallet'] = $user->wallet ?? 0;
            $input['f_wallet'] = ($user->wallet ?? 0) + $input['amount'];
            $input['name'] = "Wallet";
            $input['status'] = "successful";
            $input['date'] = Carbon::now();
            $input['ip_address'] = request()->ip();
            $input['device'] = request()->userAgent();
            $input['extra'] = "Wallet funded by " . Auth::user()->email;
            $input['code'] = "fund_wallet";
            $input['type'] = "fund_wallet";
            $input['description'] = "wallet funded successfully " . ($input['odescription'] ?? '');

            Transaction::create($input);

            $user->wallet += $input['amount'];
            $user->save();

            $user->notify(new WalletFundingNotification($input['amount'], $input['reference_id']));

            $emailJob = (new FundwalletJob($user))->delay(Carbon::now()->addSeconds(30));
            dispatch($emailJob);


            return redirect('fundwallet')->withToast($user->first_name . ' wallet funded successfully');
            //            }catch(\Exception $e){
            //                DB::rollback();
            //                return redirect('fundwallet')->withToast('Error funding wallet', 'danger');
            //            }
        } else {
            DB::rollback();
            return redirect('fundwallet')->withToast('Error funding wallet, check your input and try again', 'danger');
        }
    }

    public function charge_customer(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
            'description' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.'
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
                if (!$user) {
                    return redirect('chargecustomer')->withToast('Email or Phone number does not exist.', 'danger');
                }

                $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if ($user->company_id != auth()->user()->company_id) {
                    return redirect('chargecustomer')->withToast('Email or Phone number does not exist.', 'danger');
                }

                $input['company_id'] = $user->company_id;
                $input['user_id'] = $user->id;
                $input['i_wallet'] = $user->wallet ?? 0;
                $input['f_wallet'] = ($user->wallet ?? 0) - $input['amount'];
                $input['name'] = "Wallet Charges";
                $input['status'] = "successful";
                $input['date'] = Carbon::now();
                $input['ip_address'] = request()->ip();
                $input['device'] = request()->userAgent();
                $input['extra'] = "Wallet charged by " . Auth::user()->email;
                $input['code'] = "charge_customer";
                $input['type'] = "charge_customer";

                $transaction =   Transaction::create($input);

                ServiceCharge::create([
                    'name' => $transaction->name . 'charges',
                    'user_id' => $transaction->user_id,
                    'transaction_id' => $transaction->id,
                    'amount' => ServiceCharge::calculateServiceCharge($transaction->amount),
                    'charge_date' => now()->toDateString()
                ]);


                $user->wallet -= $input['amount'];
                $user->save();

                $user->notify(new TransactionNotification($transaction->amount, 'charge', $transaction->description ?? null));

                //                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
                //                dispatch($emailJob);


                return redirect('chargecustomer')->withToast($user->first_name . ' wallet charged successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('chargecustomer')->withToast('Error charging customer', 'danger');
            }
        } else {
            DB::rollback();
            return redirect('chargecustomer')->withToast('Error charging customer, check your input and try again', 'danger');
        }
    }

    public function post_airtime_transaction(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'user_name' => 'required',
            'amount' => 'required',
            'phoneno' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.'
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $user = User::where("email", $input['user_name'])->orWhere("phoneno", $input['user_name'])->exists();
                if (!$user) {
                    return redirect('postairtimetransaction')->withToast('Email or Phone number does not exist.', 'danger');
                }

                $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if ($user->company_id != auth()->user()->company_id) {
                    return redirect('postairtimetransaction')->withToast('Email or Phone number does not exist.', 'danger');
                }

                $input['company_id'] = $user->company_id;
                $input['user_id'] = $user->id;
                $input['i_wallet'] = $user->wallet ?? 0;
                $input['f_wallet'] = ($user->wallet ?? 0) - $input['amount'];
                $input['name'] = "Transaction";
                $input['status'] = "successful";
                $input['date'] = Carbon::now();
                $input['ip_address'] = request()->ip();
                $input['device'] = request()->userAgent();
                $input['extra'] = "Transaction posted by " . Auth::user()->email;
                $input['code'] = "post_transaction";
                $input['type'] = "airtime";
                $input['description'] = "airtime " . $input['amount'] . " on " . $input['phoneno'];

                Transaction::create($input);

                $user->wallet -= $input['amount'];
                $user->save();

                $user->notify(new TransactionNotification($input['amount'], 'airtime', $input['description'] ?? null));

                //                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
                //                dispatch($emailJob);


                return redirect('postairtimetransaction')->withToast($user->first_name . ' transaction posted successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('postairtimetransaction')->withToast('Error posting transaction', 'danger');
            }
        } else {
            DB::rollback();
            return redirect('postairtimetransaction')->withToast('Error posting , check your input and try again', 'danger');
        }
    }
    public function recharge_card(Request $request)
    {

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
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.'
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->exists();
                if (!$user) {
                    return redirect('rechargecard')->withToast('Email or Phone number does not exist.', 'danger');
                }

                $user = User::where("email", "=", $input['user_name'])->orWhere("phoneno", "=", $input['user_name'])->first();

                if ($user->company_id != auth()->user()->company_id) {
                    return redirect('rechargecard')->withToast('Email or Phone number does not exist.', 'danger');
                }

                $input['description'] = $input['network'] . " rechargecard " . $input['amount'] . " of " . $input['quantity'] . " quantity";
                $amount = $input['amount'] * $input['quantity'];
                $input['amount'] = $amount;
                $input['company_id'] = $user->company_id;
                $input['user_id'] = $user->id;
                $input['i_wallet'] = $user->wallet ?? 0;
                $input['f_wallet'] = ($user->wallet ?? 0) - $amount;
                $input['name'] = "Recharge Card";
                $input['status'] = "successful";
                $input['date'] = Carbon::now();
                $input['ip_address'] = request()->ip();
                $input['device'] = request()->userAgent();
                $input['extra'] = "Transaction posted by " . Auth::user()->email;
                $input['code'] = "recharge_card";
                $input['type'] = $input['network'];

                Transaction::create($input);

                $user->wallet -= $amount;
                $user->save();

                $user->notify(new TransactionNotification($amount, 'recharge_card', $input['description'] ?? null));

                //                $emailJob = (new FundwalletJob())->delay(Carbon::now()->addSeconds(30));
                //                dispatch($emailJob);

                return redirect('rechargecard')->withToast($user->first_name . ' recharge card sent successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('rechargecard')->withToast('Error sending recharge card', 'danger');
            }
        } else {
            DB::rollback();
            return redirect('rechargecard')->withToast('Error computing recharge card , check your input and try again', 'danger');
        }
    }

    public function reversal(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'id' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.'
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $tran = Transaction::where("id", "=", $input['id'])->exists();
                if (!$tran) {
                    return redirect('reversal')->withToast('Transaction ID does not exist.', 'danger');
                }

                $tran = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                    ->select('transactions.*', 'users.first_name', 'users.last_name')
                    ->where('transactions.id', '=', $input['id'])
                    ->first();

                if ($tran->company_id != auth()->user()->company_id) {
                    return redirect('reversal')->withToast('Transaction ID does not exist.', 'danger');
                }

                if ($tran->name == 'Reversal') {
                    return redirect('reversal')->withToast('Reversed Transaction can not be reversed', 'danger');
                }

                $rtran = Transaction::where([['description', 'LIKE', '%' . $tran->description . '%'], ['name', '=', 'Reversal']])->exists();

                if ($rtran) {
                    return redirect('reversal')->withToast('Transaction can only be reversed once', 'danger');
                }

                return view('reversal', ['data' => $tran, 't' => true]);
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('reversal')->withToast('Error fetching transaction details', 'danger');
            }
        } else {
            DB::rollback();
            return redirect('reversal')->withToast('Error fetching transaction details , check your input and try again', 'danger');
        }
    }


    public function reversalpost(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'id' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.'
        );

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $tran = Transaction::where("id", "=", $input['id'])->exists();
                if (!$tran) {
                    return redirect('reversal')->withToast('Transaction ID does not exist.', 'danger');
                }

                $tran = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
                    ->select('transactions.*', 'users.first_name', 'users.last_name')
                    ->where('transactions.id', '=', $input['id'])
                    ->first();

                if ($tran->company_id != auth()->user()->company_id) {
                    return redirect('reversal')->withToast('Transaction ID does not exist.', 'danger');
                }

                if ($tran->name == 'Reversal') {
                    return redirect('reversal')->withToast('Reversed Transaction can not be reversed', 'danger');
                }

                $rtran = Transaction::where([['description', 'LIKE', '%' . $tran->description . '%'], ['name', '=', 'Reversal']])->exists();

                if ($rtran) {
                    return redirect('reversal')->withToast('Transaction can only be reversed once', 'danger');
                }

                return redirect('reversal')->withToast('Reversal posted successfully');
            } catch (\Exception $e) {
                DB::rollback();
                return redirect('reversal')->withToast('Error fetching transaction details', 'danger');
            }
        } else {
            DB::rollback();
            return redirect('reversal')->withToast('Error fetching transaction details , check your input and try again', 'danger');
        }
    }

    public function sendEmail()
    {
        $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(3));
        dispatch($emailJob);

        echo 'email sent';
    }


    public function showReversals()
    {
        $sn = 1;
        $transactions = Transaction::with(['user', 'company'])->where('user_id', auth()->user()->id)->where('type', 'reversal')->get();
        return view('reversal_list', compact('transactions', 'sn'));
    }


    public function postingList(){
        $transactions = Transaction::with('user')->where('user_id', auth()->user()->id)->get();
        return view('posting_list', compact('transactions'));
    }
}
