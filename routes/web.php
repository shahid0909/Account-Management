<?php

use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\configuration\CaldenderController;
use App\Http\Controllers\backend\HomeController;
use App\Http\Controllers\backend\JournalVoucherController;
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


    //-------------------Calender Setup----------------------------
    Route::group(['name' => 'calender', 'as' => 'calender.'], function () {
        Route::get('/calender-setup', [CaldenderController::class, 'index'])->name('index');
        Route::get('/calender-datatable', [CaldenderController::class, 'datatable'])->name('datatable');
    });

    //-------------------------------------Journal Voucher----------------------------------------

    Route::group(['name' => 'journal-voucher', 'as' => 'journal-voucher.'], function () {
        Route::get('/journal-voucher', [JournalVoucherController::class, 'index'])->name('index');
    });

});
