<?php

use App\Http\Controllers\backend\AjaxController;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\configuration\CaldenderController;
use App\Http\Controllers\backend\GLReportController;
use App\Http\Controllers\backend\HomeController;
use App\Http\Controllers\backend\JournalVoucherController;
use App\Http\Controllers\backend\TransactionController;
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

//Route::get('/', function () {
//    return redirect()->route('login');
//});

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('login-post', [AuthController::class, 'loginUser']);
Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');


    Route::group(['name' => 'ajax', 'as' => 'ajax.'], function () {
        Route::get('/get-current-posting-period', [AjaxController::class, 'getCurrentPostingPeriod'])->name('get-current-posting-period');
        Route::post('/get-account-details', [AjaxController::class, 'getAccountDetails'])->name('get-account-details');
        Route::post('/get-account-datatable', [AjaxController::class, 'glAccDatatable'])->name('get-account-datatable');
        Route::post('/get-account-details', [AjaxController::class, 'glAccDetails'])->name('get-account-details');
    });


    //-------------------Calender Setup----------------------------
    Route::group(['name' => 'calender', 'as' => 'calender.'], function () {
        Route::get('/calender-setup', [CaldenderController::class, 'index'])->name('index');
        Route::get('/calender-datatable', [CaldenderController::class, 'datatable'])->name('datatable');
    });

    //-------------------------------------Journal Voucher----------------------------------------

    Route::group(['name' => 'journal-voucher', 'as' => 'journal-voucher.'], function () {
        Route::get('/journal-voucher', [JournalVoucherController::class, 'index'])->name('index');
        Route::post('/journal-voucher-store', [JournalVoucherController::class, 'store'])->name('store');
        Route::get('/transaction-list', [JournalVoucherController::class, 'listIndex'])->name('list');
        Route::get('/transaction-datatable', [JournalVoucherController::class, 'datatable'])->name('datatable');
        Route::get('/gl-transaction-pdf/id',[JournalVoucherController::class,'glTransactionPrint'])->name('gl-transaction-print');

    });
    Route::group(['name' => 'transaction', 'as' => 'transaction.'], function () {
        Route::get('/transaction-authorize', [TransactionController::class, 'index'])->name('index');
        Route::get('/transaction-authorize-datatable', [TransactionController::class, 'datatable'])->name('datatable');
        Route::get('/transaction-details/{id}/{moduleId}', [TransactionController::class, 'details'])->name('details');
//        Route::get('/transaction-list', [JournalVoucherController::class, 'listIndex'])->name('list');
        });


//    ------------------------GL REPORT----------------------


    Route::group(['name' => 'gl-report', 'as' => 'gl-report.'], function () {
        Route::get('/gl-report', [GLReportController::class, 'index'])->name('index');
        });


});
