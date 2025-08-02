<?php

use App\Http\Controllers\Api\AuthenticateController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\OthersController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KorapayWebhookController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['status' => 1, 'message'=>'Fetched successfully', 'data'=> $request->user()]);
});

Route::post('signup', [AuthenticateController::class, 'signup']);
Route::post('login', [AuthenticateController::class, 'login']);
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
    Route::post('updateprofile', [UserController::class, 'updateProfile'])->name('updateProfile');
    Route::post('changepassword', [UserController::class, 'changepassword'])->name('changepassword');
    Route::get('vaccts', [UserController::class, 'vaccts'])->name('vaccts');

    Route::post('buy/data', [ServerController::class, 'buydata'])->name('buydata');
    Route::post('buy/airtime', [ServerController::class, 'buyairtime'])->name('buyairtime');
    Route::post('buy/paytv', [ServerController::class, 'paytv'])->name('paytv');
    Route::post('buy/a2c', [ServerController::class, 'a2c'])->name('a2c');
    Route::post('buy/electricity', [ServerController::class, 'buyelectricity'])->name('buyelectricity');
    Route::post('buy/transfer', 'RequestServerController@buytransfer')->name('buytransfer')->middleware("apphelper");

});

Route::get('/user/image/{filename}', function ($filename)
{
    $path = storage_path('app/public/user/' . $filename);

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




