<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpensesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return 'Tabunganku API - Laravel ' . app()->version();
});

Route::group(['prefix' => '/auth'], function () {
   Route::post('/login', [AuthController::class, 'login'])->name('login');
   Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::group(['prefix' => '/deposit', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/user-balance', [HomeController::class, 'getBalance']);
    Route::get('/user-balance-percentage', [HomeController::class, 'getBalancePercentage']);
});

Route::group(['prefix' => '/transaction', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/recent-transaction', [HomeController::class, 'getRecentTransaction']);
    Route::get('/expenses-chart', [ExpensesController::class, 'getExpensesChart']);
    Route::get('/expenses-by-date', [ExpensesController::class, 'getExpensesByDate']);
});

Route::group(['prefix' => '/goals', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/recent-goals', [HomeController::class, 'getRecentGoals']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
