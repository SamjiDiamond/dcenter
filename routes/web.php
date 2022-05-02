<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::group(['middleware' => ['role:ceo-1']], function () {
    Route::get('/servicess', function () {
        return view('config_services');
    });
});

Route::get('/queue', 'UltilityController@queue')->name('queue');

Route::get('/confirm-password', function () {
    return view('auth.confirm-password');
})->middleware('auth')->name('password.confirm');

Route::post('/confirm-password', function (Request $request) {
    if (! Hash::check($request->password, $request->user()->password)) {
        return back()->withErrors([
            'password' => ['The provided password does not match our records.']
        ]);
    }

    $request->session()->passwordConfirmed();

    return redirect()->intended();
})->middleware(['auth', 'throttle:6,1'])->name('password.confirm');



Route::group(['middleware' => 'auth:sanctum', 'verified', 'subware'], function() {

    Route::get('/dashboard', [HomeController::class, 'index'] )->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'] )->name('home');

    Route::get('/roles', [RoleController::class, 'index'])->name('role.list');
    Route::get('/role/{id}', [RoleController::class, 'show'])->name('role.view');
    Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware(['middleware' => 'password.confirm']);
    Route::post('/role-update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::post('/role-create', [RoleController::class, 'store'])->name('role.create');
    Route::get('/role-delete/{id}', [RoleController::class, 'destroy'])->name('role.delete');

    Route::get('/users', [UserController::class, 'userslist'])->name('user.list');
    Route::get('/user/{id}', [UserController::class, 'userdetails'])->name('user.view');
    Route::get('/user-edit/{id}', [UserController::class, 'useredit'])->name('user.edit')->middleware(['middleware' => 'password.confirm']);
    Route::get('/user-disable/{id}', [UserController::class, 'userdisable'])->name('user.disable');
    Route::get('/user-enable/{id}', [UserController::class, 'userenable'])->name('user.enable');
    Route::post('/user-update/{id}', [UserController::class, 'userupdate'])->name('user.update');
    Route::post('/upload-image', [UserController::class, 'uploadImage'])->name('user.uploadImage');

    Route::get('/admin', [UserController::class, 'index'])->name('admin.list');
    Route::get('/admin-disable/{id}', [UserController::class, 'disable'])->name('admin.disable');
    Route::get('/admin-enable/{id}', [UserController::class, 'enable'])->name('admin.enable');
    Route::get('/admin-edit/{id}', [UserController::class, 'edit'])->name('admin.edit')->middleware(['middleware' => 'password.confirm']);
    Route::post('/admin-update/{id}', [UserController::class, 'update'])->name('admin.update');
    Route::post('/admin-create', [UserController::class, 'store'])->name('admin.create');

    Route::get('/services', [ServicesController::class, 'index'])->name('services.list');
    Route::get('/services-airtime-edit/{id}', [ServicesController::class, 'airtimeedit'])->name('Edit airtime services')->middleware(['middleware' => 'password.confirm']);
    Route::post('/services-airtime-update/{id}', [ServicesController::class, 'airtimeupdate'])->name('Update airtime services');
    Route::get('/services-airtime-sync', [ServicesController::class, 'synair'])->name('airtime.services.sync');
    Route::get('/services-data-edit/{id}', [ServicesController::class, 'dataedit'])->name('Edit data services')->middleware(['middleware' => 'password.confirm']);
    Route::post('/services-data-update/{id}', [ServicesController::class, 'dataupdate'])->name('Update data services');
    Route::get('/services-data-sync', [ServicesController::class, 'syndata'])->name('data.services.sync');
    Route::get('/services-tv-edit/{id}', [ServicesController::class, 'tvedit'])->name('Edit tv services')->middleware(['middleware' => 'password.confirm']);
    Route::post('/services-tv-update/{id}', [ServicesController::class, 'tvupdate'])->name('Update tv services');
    Route::get('/services-tv-sync', [ServicesController::class, 'syntv'])->name('tv.services.sync');
    Route::get('/services-electricity-edit/{id}', [ServicesController::class, 'electedit'])->name('Edit elect services')->middleware(['middleware' => 'password.confirm']);
    Route::post('/services-electricity-update/{id}', [ServicesController::class, 'electupdate'])->name('Update elect services');
    Route::get('/services-electricity-sync', [ServicesController::class, 'synelec'])->name('elec.services.sync');
    Route::get('/services-transfer-edit/{id}', [ServicesController::class, 'transferedit'])->name('Edit transfer services')->middleware(['middleware' => 'password.confirm']);
    Route::post('/services-transfer-update/{id}', [ServicesController::class, 'transferupdate'])->name('Update transfer services');
    Route::get('/services-transfer-sync', [ServicesController::class, 'syntran'])->name('transfer.services.sync');

    Route::post('/pay', [BillingController::class, 'redirectToGateway'])->name('pay');
    Route::get('/verify/{reference_id}', [BillingController::class, 'verifyPayment'])->name('verify.payment');
    Route::get('/payment/callback/{id}', [BillingController::class,'handleGatewayCallback']);

    Route::get('/billing', [BillingController::class, 'index'])->name('plans');
    Route::get('/plan/{plan}', [BillingController::class, 'invoice'])->name('planshow');
    Route::post('/subscription', [BillingController::class, 'create'])->name('subscription.create');
    Route::post('/subscription-cancel', [BillingController::class, 'cancelsub'])->name('subscription.cancel');
    Route::post('/subscription-enable', [BillingController::class, 'enablesub'])->name('subscription.enable');
    Route::get('/subscriptions', [BillingController::class, 'showsub'])->name('subscriptions.list');
    Route::post('/invoices', [BillingController::class, 'invoices'])->name('subscriptions.invoices');

    Route::get('/smspayment', function () {return view('sms_payment');})->name('sms.pay');
    Route::post('/smspayment', [BillingController::class, 'sms_payment'])->name('sms.payment');
    Route::get('/smspayments', [BillingController::class, 'sms_payments'])->name('sms.payments');
    Route::get('/smstransactions', [BillingController::class, 'sms_transactions'])->name('sms.transactions');

    Route::get('/usertransactions', [HistoryController::class, 'usertransaction'])->name('user.transactions');
    Route::get('/companywallethistory', [HistoryController::class, 'companywallet'])->name('company.wallet');

    Route::get('/fundwallet', function () {
        return view('fund_wallet');
    });
    Route::post('/fundwallet', [TransactionController::class, 'fund_wallet'])->name('user.fundwallet');
    Route::get('/chargecustomer', function () {
        return view('charge_customer');
    });
    Route::post('/chargecustomer', 'TransactionController@charge_customer')->name('user.charge_customer');
    Route::get('/postairtimetransaction', function () {
        return view('post_airtime_transaction');
    });
    Route::post('/postairtimetransaction', 'TransactionController@post_airtime_transaction')->name('user.post_airtime_transaction');
    Route::get('/rechargecard', function () {
        return view('recharge_card');
    });
    Route::post('/rechargecard', 'TransactionController@recharge_card')->name('user.rechargecard');
    Route::get('/reversal', function () {
        return view('reversal');
    });
    Route::post('/reversal', 'TransactionController@reversal')->name('transaction.reversal');
    Route::post('/reversal-post', 'TransactionController@reversalpost')->name('transaction.reversal.post');


    Route::get('/addfaq', function () {
        return view('add_faq');
    });

    Route::get('/addplan', function () {
        return view('add_plan');
    });

    Route::get('/addpermission', function () {
        return view('add_permission');
    });

    Route::get('user/invoice/{invoice}', function (Request $request, $invoiceId) {
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor'  => 'Your Company',
            'product' => 'Your Product',
        ]);
    });



    Route::post('order-post', ['as'=>'order-post','uses'=>'UserController@orderPost']);
});

Route::get('/billings', function () {
    return view('users.billing');
});

Route::get('/invoice', function () {
    return view('invoice_usersub');
});

//Route::get('/admin', function () {
//    return view('admins');
//});

Route::get('/faq', function () {
    return view('d_faq');
});


Route::get('/sa', function () {
    return view('settings_sms');
});


Route::get('/account_ledger', function () {
    return view('report_account_ledger');
});

Route::get('/new_account', function () {
    return view('report_newaccount');
});

Route::get('/report_deposit', function () {
    return view('report_deposit_monitoring');
});

Route::get('/report_audit_trail', function () {
    return view('report_audittrail');
});

Route::get('/report_service_charge', function () {
    return view('report_service_charge');
});

Route::get('/fundwalletmail', function () {
    return (new App\Mail\FundwalletMail())->render();
});

Route::get('/newaccountmail', function () {
    return (new App\Mail\NewaccountMail())->render();
});

Route::get('/newmessagemail', function () {
    return (new App\Mail\NewmessageMail())->render();
});

Route::get('/newtransactionmail', function () {
    return (new App\Mail\NewtransactionMail())->render();
});


Route::get('/payout', [PayoutController::class, 'create'])->name('admin.payout.create');
Route::post('/store', [PayoutController::class, 'store'])->name('admin.payment.store');


Route::get('checkout', [CheckoutController::class, 'create'])->name('admin.checkout.create');

Route::get('settings', [SettingsController::class, 'index'])->name('admin.settings.index');
