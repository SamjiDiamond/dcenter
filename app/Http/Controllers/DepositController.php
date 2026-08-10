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
        // NOTE: the previous placeholder "Scheduled Maintenance Notice" blast here
        // sent an unread notification to EVERY user on EVERY page load of this
        // report, which is why the bell badge never cleared. If you want to
        // broadcast a notice, do it once via tinker, not on page render:
        //
        //   Notification::send(User::all(), new UserNotification('...'));

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
