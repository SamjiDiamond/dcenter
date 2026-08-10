<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\AuthenticateController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OthersController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TwoFactorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KorapayWebhookController;
use App\Http\Controllers\Api\WithdrawalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);

Route::post('signup', [AuthenticateController::class, 'signup']);
Route::post('login', [AuthenticateController::class, 'login']);
Route::post('two-factor/verify-login', [TwoFactorController::class, 'verifyLogin'])->middleware('throttle:10,1');
Route::post('resetpassword', [AuthenticateController::class, 'resetpassword']);

Route::get('companys', [CompanyController::class, 'index'])->name('companys');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('airtimeconfig', [TransactionController::class, 'airtimeConfig'])->name('airtime');
    Route::get('dataconfig/{network}', [TransactionController::class, 'dataConfig'])->name('data');
    Route::get('tvconfig/{provider}', [TransactionController::class, 'tvConfig'])->name('tv');
    Route::get('electricityconfig', [TransactionController::class, 'electricityConfig'])->name('electricity');
    Route::get('banktransferconfig', [TransactionController::class, 'banktransferConfig'])->name('banktransfer');

    Route::get('transactions', [TransactionController::class, 'transactions'])->name('transactions');
    Route::get('transaction/{id}', [TransactionController::class, 'transaction'])->name('transaction');

    Route::post('validatebankaccount', [TransactionController::class, 'ValidateBankAccount'])->name('bankactval');
    Route::post('validateuseraccount', [TransactionController::class, 'ValidateUserAccount'])->name('useractval');
    Route::post('validatetv', [TransactionController::class, 'ValidateTV'])->name('tvval');
    Route::post('validatemeter', [TransactionController::class, 'ValidateMeter'])->name('meterval');

    Route::get('faq', [OthersController::class, 'faq'])->name('faq');

    Route::get('companydetails', [CompanyController::class, 'getcompany'])->name('company');

    Route::post('uploaddp', [UserController::class, 'uploaddp'])->name('uploaddp');
    Route::post('remove-photo', [UserController::class, 'removePhoto'])->name('removePhoto');
    Route::post('updateprofile', [UserController::class, 'updateProfile'])->name('updateProfile');
    Route::post('changepassword', [UserController::class, 'changepassword'])->name('changepassword');
    Route::get('vaccts', [UserController::class, 'vaccts'])->name('vaccts');

    // Two-factor authentication (email OTP)
    Route::post('two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('two-factor/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm')->middleware('throttle:6,1');
    Route::post('two-factor/resend', [TwoFactorController::class, 'resend'])->name('two-factor.resend')->middleware('throttle:6,1');
    Route::delete('two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    // Account deletion (scheduled)
    Route::post('account/delete-request', [AccountController::class, 'deleteRequest'])->name('account.delete.request');
    Route::delete('account/delete-request', [AccountController::class, 'cancelDeleteRequest'])->name('account.delete.cancel');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Team members
    Route::get('team-members', [TeamMemberController::class, 'index'])->name('team-members.index');
    Route::post('team-members/invite', [TeamMemberController::class, 'invite'])->name('team-members.invite');
    Route::delete('team-members/{id}', [TeamMemberController::class, 'remove'])->name('team-members.remove');

    // Audit log
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::post('buy/data', [ServerController::class, 'buydata'])->name('buydata');
    Route::post('buy/airtime', [ServerController::class, 'buyairtime'])->name('buyairtime');
    Route::post('buy/paytv', [ServerController::class, 'paytv'])->name('paytv');
    Route::post('buy/a2c', [ServerController::class, 'a2c'])->name('a2c');
    Route::post('buy/electricity', [ServerController::class, 'buyelectricity'])->name('buyelectricity');
    Route::post('buy/transfer', [ServerController::class, 'buytransfer'])->name('buytransfer');

});

Route::get('/user/image/{filename}', function ($filename)
{
    $path = storage_path('app/public/users/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::get('/company/image/{filename}', function ($filename)
{
    $path = storage_path('app/public/company/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});


Route::group(['prefix' => 'hook'], function () {
    Route::post('korapay',[KorapayWebhookController::class, 'webhookUrl']);

});




