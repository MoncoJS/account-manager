<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MonthlyBudgetController;


Route::get('/', function () { return redirect('/bank-accounts'); });

Route::resource('bank-accounts', BankAccountController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('categories', CategoryController::class);
Route::resource('monthly-budgets', MonthlyBudgetController::class);
Route::get('tax/{year}', [TransactionController::class, 'calculateTax'])->name('tax.calculate');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
