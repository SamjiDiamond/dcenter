<?php

use App\Models\User;
use App\Mail\FundwalletMail;
use App\Mail\newAccountMail;
use App\Mail\NewmessageMail;
use Illuminate\Http\Request;
use App\Mail\transactionMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LockScreenController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UltilityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ServiceChargeController;
use App\Http\Controllers\TemplateVersionController;

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

Route::get('/queue', [UltilityController::class, 'queue'])->name('queue');

Route::get('/confirm-password', function () {
    return view('auth.confirm-password');
})->middleware('auth')->name('password.confirm');

Route::post('/confirm-password', function (Request $request) {
    if (!Hash::check($request->password, $request->user()->password)) {
        return back()->withErrors([
            'password' => ['The provided password does not match our records.']
        ]);
    }

    $request->session()->passwordConfirmed();

    return redirect()->intended();
})->middleware(['auth', 'throttle:6,1'])->name('password.confirm');



Route::group(['middleware' => ['auth:sanctum', 'verified', 'subware', 'lockscreen']], function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/roles', [RoleController::class, 'index'])->name('role.list');
    Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware(['middleware' => 'password.confirm']);
    Route::post('/role-update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::post('/role-create', [RoleController::class, 'store'])->name('role.create');
    Route::get('/role-delete/{id}', [RoleController::class, 'destroy'])->name('role.delete');

    Route::get('/users', [UserController::class, 'userslist'])->name('user.list');
    Route::get('/user/{user}', [UserController::class, 'userdetails'])->name('user.view');
    Route::get('/user-edit/{user}', [UserController::class, 'useredit'])->name('user.edit')->middleware(['middleware' => 'password.confirm']);
    Route::get('/user-disable/{user}', [UserController::class, 'userdisable'])->name('user.disable');
    Route::get('/user-enable/{user}', [UserController::class, 'userenable'])->name('user.enable');
    Route::post('/user-update/{user}', [UserController::class, 'userupdate'])->name('user.update');
    Route::post('/upload-image', [UserController::class, 'uploadImage'])->name('user.uploadImage');

    Route::get('/admin', [UserController::class, 'index'])->name('admin.list');
    Route::get('/admin-disable/{user}', [UserController::class, 'disable'])->name('admin.disable');
    Route::get('/admin-enable/{user}', [UserController::class, 'enable'])->name('admin.enable');
    Route::get('/admin-edit/{user}', [UserController::class, 'edit'])->name('admin.edit')->middleware(['middleware' => 'password.confirm']);
    Route::post('/admin-update/{user}', [UserController::class, 'update'])->name('admin.update');
    Route::post('/admin-create', [UserController::class, 'store'])->name('admin.create');

    //for faqs, plan, and permission

    Route::post('/faqss', [TransactionController::class,'faqs']);
    Route::post('/plans', [TransactionController::class,'planss']);
    Route::post('/permission', [TransactionController::class,'permissions']);
    Route::get('/vfa', [TransactionController::class,'viewfaq']);


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


    Route::get('/recharge-card-list', [ServicesController::class, 'rechargecardList'])->name('rechargecard.list');

    Route::get('/smspayment', function () {
        return view('sms_payment');
    })->name('sms.pay');
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
    Route::post('/chargecustomer', [TransactionController::class, 'charge_customer'])->name('user.charge_customer');
    Route::get('/postairtimetransaction', function () {
        return view('post_airtime_transaction');
    });
    Route::post('/postairtimetransaction', [TransactionController::class, 'post_airtime_transaction'])->name('user.post_airtime_transaction');
    Route::get('/rechargecard', function () {
        return view('recharge_card');
    });
    Route::post('/rechargecard', [TransactionController::class, 'recharge_card'])->name('user.rechargecard');
    Route::get('/reversal', function () {
        return view('reversal');
    });

    Route::get('/reversal-list', [TransactionController::class, 'showReversals'])->name('transaction.reversal.show');

    Route::post('/reversal', [TransactionController::class, 'reversal'])->name('transaction.reversal');
    Route::post('/reversal-post', [TransactionController::class, 'reversalpost'])->name('transaction.reversal.post');


    Route::get('/posting-list', [TransactionController::class, 'postingList'])->name('transaction.posting.list');


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



    Route::post('order-post', [UserController::class, 'orderPost'])->name('order-post');
});

Route::group(['middleware' => ['auth:sanctum', 'verified', 'lockscreen']], function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('plans');
    Route::get('/plan/{plan}', [BillingController::class, 'invoice'])->name('planshow');
    Route::post('/subscription', [BillingController::class, 'create'])->name('subscription.create');
    Route::post('/subscription-cancel', [BillingController::class, 'cancelsub'])->name('subscription.cancel');
    Route::post('/subscription-enable', [BillingController::class, 'enablesub'])->name('subscription.enable');
    Route::get('/subscriptions', [BillingController::class, 'showsub'])->name('subscriptions.list');
    Route::post('/invoices', [BillingController::class, 'invoices'])->name('subscriptions.invoices');
    Route::post('/pay', [BillingController::class, 'redirectToGateway'])->name('pay');
    Route::get('/verify/{reference_id}', [BillingController::class, 'verifyPayment'])->name('verify.payment');
    Route::get('/payment/callback/{id}', [BillingController::class, 'handleGatewayCallback']);


    Route::resource('email-templates', TemplateController::class);
    Route::resource('email-template-versions', TemplateVersionController::class);

    Route::get('/account_ledger', [TransactionController::class, 'accountLedger'])->name('transaction.account.ledger');
    Route::get('/company_wallet_ledger', [TransactionController::class, 'fetchTransaction'])->name('transaction.wallet');
    Route::get('/transaction_list', [TransactionController::class, 'index'])->name('transaction.index');

    Route::get('/new_account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/report_audit_trail', [AuditController::class, 'index'])->name('audit.trail.index');

    Route::get('/report_deposit', [DepositController::class, 'index'])->name('report.deposit.index');
    Route::get('/bank_transfer_deposit', [DepositController::class, 'bankTransfer'])->name('bank.tranfer.deposit');
    Route::get('/atm_deposit', [DepositController::class, 'atmDeposit'])->name('atm.deposit');

    Route::get('notification-read/{id}', [NotificationController::class, 'markNotificationAsRead'])->name('notification.read');
    Route::get('notifications/count', [NotificationController::class, 'countJson'])->name('notification.count');
    Route::get('notifications/feed', [NotificationController::class, 'feed'])->name('notification.feed');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notification.index');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notification.read-all');

    Route::get('/report_service_charge', [ServiceChargeController::class, 'index'])->name('service.charge.index');


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

    Route::get('settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::get('settings/system', [SettingsController::class, 'system'])->name('admin.settings.system');
    Route::post('settings/system', [SettingsController::class, 'updateSystem'])->name('admin.settings.update');

    // Account Settings (web admin)
    Route::get('account-settings', [AccountSettingsController::class, 'index'])->name('account.settings.index');
    Route::get('account-settings/team', [TeamMemberController::class, 'index'])->name('team.index');
    Route::post('account-settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('account.settings.profile.update');
    Route::post('account-settings/two-factor/enable', [AccountSettingsController::class, 'enableTwoFactor'])->name('account.settings.two-factor.enable');
    Route::post('account-settings/two-factor/confirm', [AccountSettingsController::class, 'confirmTwoFactor'])->name('account.settings.two-factor.confirm');
    Route::post('account-settings/two-factor/resend', [AccountSettingsController::class, 'resendTwoFactor'])->name('account.settings.two-factor.resend');
    Route::post('account-settings/two-factor/disable', [AccountSettingsController::class, 'disableTwoFactor'])->name('account.settings.two-factor.disable');
    Route::post('account-settings/photo', [AccountSettingsController::class, 'uploadPhoto'])->name('account.settings.photo.upload');
    Route::post('account-settings/photo/remove', [AccountSettingsController::class, 'removePhoto'])->name('account.settings.photo.remove');
    Route::post('account-settings/delete-request', [AccountSettingsController::class, 'deleteRequest'])->name('account.settings.delete.request');
    Route::post('account-settings/delete-cancel', [AccountSettingsController::class, 'cancelDeleteRequest'])->name('account.settings.delete.cancel');
    Route::post('account-settings/team/invite', [TeamMemberController::class, 'inviteMember'])->name('team.invite');
    Route::post('account-settings/team/remove/{user}', [TeamMemberController::class, 'removeMember'])->name('team.remove');
});



Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/lock-screen', [LockScreenController::class, 'index'])->name('lock.screen');
    Route::post('/lock-screen-login', [LockScreenController::class, 'login'])->name('lock.screen.login');
});







// Route::get('/account_ledger', function () {
//     return view('report_account_ledger');
// });


// Route::get('/new_account', function () {
//     return view('report_newaccount');
// });






Route::get('/fundwalletmail', function () {
    //dd('here');
    $user = auth()->user();
    return view('email-templates.fund-wallet');
    //  dd($user);
    // return (new App\Mail\FundwalletMail($user))->render();
});

Route::get('/newaccountmail', function () {
    $user = auth()->user();
    return (new App\Mail\newAccountMail($user))->render();
});

Route::get('/newmessagemail', function () {
    $user = auth()->user();
    return (new App\Mail\NewmessageMail($user))->render();
});

Route::get('/newtransactionmail', function () {
    $user = auth()->user();
    return (new App\Mail\transactionMail($user))->render();
});


// Route::get('notify', function(){

//     // Generate a new notification class
//      Artisan::call('migrate', ['--path' => 'database/migrations/2023_08_21_203711_create_notifications_table.php']);

//     $output = Artisan::output();
//     echo $output;
// });


Route::get('/payout', [PayoutController::class, 'create'])->name('admin.payout.create');
Route::post('/store', [PayoutController::class, 'store'])->name('admin.payment.store');


Route::get('checkout', [CheckoutController::class, 'create'])->name('admin.checkout.create');
