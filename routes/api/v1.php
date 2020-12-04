<?php

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
Route::prefix('user')->group(function (){

    Route::post('/new', [\App\Http\Controllers\api\v1\UsersController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\api\v1\UsersController::class, 'login']);

    Route::get('/profile', [\App\Http\Controllers\api\v1\UsersController::class, 'profile'])
        ->middleware('auth:sanctum');

    Route::get('/logout', [\App\Http\Controllers\api\v1\UsersController::class, 'logout'])
        ->middleware('auth:sanctum');
    Route::get('/logoutAll', [\App\Http\Controllers\api\v1\UsersController::class, 'logoutAll'])
        ->middleware('auth:sanctum');

});

Route::prefix('customer')->middleware('auth:sanctum')->group(function (){

    Route::get('/home', [\App\Http\Controllers\api\v1\Customer\HomeController::class, 'index']);
    Route::get('/service/{service}', [\App\Http\Controllers\api\v1\Customer\ServiceController::class, 'show']);

    Route::get('/orders', [\App\Http\Controllers\api\v1\Customer\OrderController::class, 'index']);
    Route::get('/orders/{order}', [\App\Http\Controllers\api\v1\Customer\OrderController::class, 'show']);
    Route::post('/orders/new/{service}', [\App\Http\Controllers\api\v1\Customer\OrderController::class, 'store']);

    Route::get('order/shipping/{order}', [\App\Http\Controllers\api\v1\Customer\OrderController::class, 'orderAddress']);
    Route::post('order/shipping/{order}', [\App\Http\Controllers\api\v1\Customer\OrderController::class, 'setShippingInfo']);
    Route::post('order/status/{order}', [\App\Http\Controllers\api\v1\Customer\OrderController::class, 'changeStatus']);

    Route::get('addresses', [\App\Http\Controllers\api\v1\Customer\AddressController::class, 'index']);
    Route::get('addresses/{userAddress}', [\App\Http\Controllers\api\v1\Customer\AddressController::class, 'show']);
    Route::post('addresses/new', [\App\Http\Controllers\api\v1\Customer\AddressController::class, 'store']);
    Route::delete('addresses/{userAddress}/delete', [\App\Http\Controllers\api\v1\Customer\AddressController::class, 'destroy']);

    Route::get('payment/methods', [\App\Http\Controllers\api\v1\PaymentMethodsController::class, 'index']);
    Route::post('order/{order}/payment/methods', [\App\Http\Controllers\api\v1\PaymentMethodsController::class, 'store']);

});

Route::prefix('chef')->middleware('auth:sanctum')->group(function (){

    Route::get('/home', [\App\Http\Controllers\api\v1\Chef\HomeController::class, 'index']);
    Route::put('/status', [\App\Http\Controllers\api\v1\Chef\HomeController::class, 'update']);

    Route::get('/orders', [\App\Http\Controllers\api\v1\Chef\OrderController::class, 'index']);
    Route::get('/orders/{order}', [\App\Http\Controllers\api\v1\Chef\OrderController::class, 'show']);
    Route::post('/order/{order}/dispatch', [\App\Http\Controllers\api\v1\Chef\OrderController::class, 'dispatchOrder']);

    Route::get('/couriers', [\App\Http\Controllers\api\v1\Chef\CourierController::class, 'index']);

    Route::get('/services', [\App\Http\Controllers\api\v1\Chef\ServiceController::class, 'index']);
    Route::get('/services/{service}/options', [\App\Http\Controllers\api\v1\Chef\ServiceController::class, 'show']);
    Route::post('/services/{service}', [\App\Http\Controllers\api\v1\Chef\ServiceController::class, 'update']);

    Route::post('/services/{service}/options', [\App\Http\Controllers\api\v1\Chef\ServiceOptionController::class, 'store']);
    Route::get('/service/options/{option}', [\App\Http\Controllers\api\v1\Chef\ServiceOptionController::class, 'show']);
    Route::put('/service/options/{option}', [\App\Http\Controllers\api\v1\Chef\ServiceOptionController::class, 'update']);
    Route::post('/service/options/{option}', [\App\Http\Controllers\api\v1\Chef\ServiceOptionController::class, 'changeStatus']);
    Route::delete('/service/options/{option}', [\App\Http\Controllers\api\v1\Chef\ServiceOptionController::class, 'destroy']);

    Route::get('/statement', [\App\Http\Controllers\api\v1\Chef\StatementController::class, 'index']);

});

Route::prefix('courier')->middleware('auth:sanctum')->group(function (){

    Route::get('/home', [\App\Http\Controllers\api\v1\Courier\HomeController::class, 'index']);
    Route::put('/status', [\App\Http\Controllers\api\v1\Courier\HomeController::class, 'update']);

    Route::get('/statement', [\App\Http\Controllers\api\v1\Courier\StatementController::class, 'index']);

});

Route::middleware('auth:sanctum')->group(function (){

    Route::get('/reviews', [\App\Http\Controllers\api\v1\ReviewController::class, 'index']);
    Route::post('/reviews/new', [\App\Http\Controllers\api\v1\ReviewController::class, 'store']);

});

