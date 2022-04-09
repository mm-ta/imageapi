<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

require __DIR__.'/auth.php';
