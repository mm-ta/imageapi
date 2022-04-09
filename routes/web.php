<?php

use App\Mail\HelloMail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Mail;

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

Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/token')->name('token.')->group(function() {
        Route::get('/create', [DashboardController::class, 'showTokenForm'])->name('showForm');
        Route::post('/create', [DashboardController::class, 'createToken'])->name('create');
        Route::post('/delete/{token}', [DashboardController::class, 'deleteToken'])->name('delete');
    });
});

// a route to test mail
Route::get('/mail', function() {
    Mail::to(env('TEST_MAIL_ADDRESS'))->send(new HelloMail());

    return new HelloMail();
});

require __DIR__.'/auth.php';
