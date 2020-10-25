<?php

use App\Http\Controllers\Api\AuthenticateController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\TransactionController;
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
    return $request->user();
});

Route::post('signup', [AuthenticateController::class, 'signup'])->name('signup');
Route::post('login', [AuthenticateController::class, 'login'])->name('login');

Route::get('companys', [CompanyController::class, 'index'])->name('companys');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('airtimeconfig', [TransactionController::class, 'airtimeConfig'])->name('airtime');
    Route::get('dataconfig/{network}', [TransactionController::class, 'dataConfig'])->name('data');
    Route::get('tvconfig/{provider}', [TransactionController::class, 'tvConfig'])->name('tv');
    Route::get('electricityconfig', [TransactionController::class, 'electricityConfig'])->name('electricity');
    Route::get('banktransferconfig', [TransactionController::class, 'banktransferConfig'])->name('banktransfer');

    Route::post('validatebankaccount', [TransactionController::class, 'ValidateBankAccount'])->name('bankactval');
    Route::post('validateuseraccount', [TransactionController::class, 'ValidateUserAccount'])->name('useractval');
    Route::post('validatetv', [TransactionController::class, 'ValidateTV'])->name('tvval');
    Route::post('validatemeter', [TransactionController::class, 'ValidateMeter'])->name('meterval');
});
