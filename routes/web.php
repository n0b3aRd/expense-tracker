<?php

use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegularExpenseController;
use App\Http\Controllers\RegularIncomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('income_categories', [IncomeCategoryController::class, 'index'])->name('income_categories.index');
    Route::get('regular_income', [RegularIncomeController::class, 'index'])->name('regular_income.index');

    Route::get('expense_categories', [ExpenseCategoryController::class, 'index'])->name('expense_categories.index');
    Route::get('regular_expense', [RegularExpenseController::class, 'index'])->name('regular_expense.index');

    Route::get('months', [MonthController::class, 'index'])->name('months.index');
    Route::get('months/{month}', [MonthController::class, 'show'])->name('months.show');
});
