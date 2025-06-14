<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        Notification::send($users, new UserNotification("🚧 Scheduled Maintenance Notice 🚧

Dear Dynamic Center Website Users,

We want to inform you that our website will undergo scheduled maintenance to enhance your browsing experience. During this time, certain features may be temporarily unavailable. Here are the details:

📅 Date: [Insert Maintenance Date] 🕒 Time: [Insert Maintenance Time] ⏲️ Expected Duration: [Insert Expected Duration]

During this maintenance period, we will be working diligently to improve our website's performance and implement necessary updates. We apologize for any inconvenience this may cause and appreciate your understanding as we strive to provide you with a more reliable and efficient browsing experience.

Thank you for choosing Dynamic Center, and we look forward to serving you better in the future!

Best regards, The Dynamic Center Team "));

     


        $customerId = request()->input('customer_id');
        $transactionId = request()->input('transaction_id');
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');

        if (request()->filled('customer_id') && request()->filled('transaction_id')) {
            $deposits = Deposit::FilterDeposits($customerId, $transactionId, $startDate, $endDate)->get();
            $initialDeposit = optional(auth()->user()->initialDeposit)->amount;
            $sn = 1;
            return view('report_deposit_monitoring', compact('deposits', 'initialDeposit', 'sn'));
        }

        return view('report_deposit_monitoring');
    }

  
    public function bankTransfer()
    {
        $customerId = request()->input('customer_id');
        $transactionId = request()->input('transaction_id');
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        $bankTransfer = Deposit::BANK_TRANSFER;

        if (request()->filled('customer_id') && request()->filled('transaction_id')) {
            $deposits = Deposit::FilterDeposits($customerId, $transactionId, $startDate, $endDate, $bankTransfer)->get();
            $initialDeposit = optional(auth()->user()->initialDeposit)->amount;
            $sn = 1;
            return view('report_bank_transfer_deposit', compact('deposits', 'initialDeposit', 'sn'));
        }

        return view('report_bank_transfer_deposit');
    }

    
    public function atmDeposit()
    {
        $customerId = request()->input('customer_id');
        $transactionId = request()->input('transaction_id');
        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        $atm = Deposit::ATM;

        if (request()->filled('customer_id') && request()->filled('transaction_id')) {
            $deposits = Deposit::FilterDeposits($customerId, $transactionId, $startDate, $endDate, $atm)->get();
            $initialDeposit = optional(auth()->user()->initialDeposit)->amount;
            $sn = 1;
            return view('report_atm_deposit', compact('deposits', 'initialDeposit', 'sn'));
        }

        return view('report_atm_deposit');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deposit $deposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
