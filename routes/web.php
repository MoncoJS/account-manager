<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () { return redirect('/bank-accounts'); });

Route::resource('bank-accounts', BankAccountController::class);
Route::resource('transactions', TransactionController::class);
Route::get('tax/{year}', [TransactionController::class, 'calculateTax']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
