<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\DayMealController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrdersController;
use Illuminate\Support\Facades\Auth;
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

// AUTHENTICATION URLS
Auth::routes();

// BACKEND URLS
Route::middleware(['role:admin'])->prefix('/')->group(static function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::resource('meal', MealController::class);
    Route::get('/day/report', [DayMealController::class, 'report']);
    Route::delete('/day/inactivate/{day}/{meal}', [DayMealController::class, 'inactivate']);
    Route::resource('day', DayMealController::class)->except(['show', 'destroy']);
    Route::resource('organization', OrganizationController::class)->except(['show']);
    Route::resource('users', UsersController::class);
    Route::get('/orders/report', [OrdersController::class, 'report']);
    Route::post('/orders/search', [OrdersController::class, 'search']);
    Route::resource('orders', OrdersController::class)->except(['update', 'edit', 'destroy']);
    Route::get('/payment/accept/{id}/{sum}', [PaymentController::class, 'accept']);
    Route::resource('payment', PaymentController::class)->except(['create', 'store', 'update']);
});
