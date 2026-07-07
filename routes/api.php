<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Brand\BrandController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Device\DeviceShareController;
use App\Http\Controllers\Device\DeviceTransferController;
use App\Http\Controllers\Device\DeviceValidationController;
use App\Http\Controllers\DeviceModel\DeviceModelController;
use App\Http\Controllers\PasswordReset\PasswordResetController;
use App\Http\Controllers\User\UserController;
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

Route::controller(AuthController::class)->prefix('auth')->name('api.auth.')->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

Route::controller(PasswordResetController::class)
    ->prefix('password-reset')
    ->name('api.password-reset.')->group(function () {
        Route::post('/', 'reset')->name('reset');
        Route::post('start', 'start')->name('start');
        Route::post('check-code', 'checkCode')->name('check-code');
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::delete('logout', 'logout')->name('api.auth.logout');
    });

    Route::controller(DashboardController::class)
        ->prefix('dashboard')
        ->name('api.dashboard')
        ->group(function () {
            Route::get('/', 'dashboard');
        });

    Route::controller(UserController::class)->group(function () {
        Route::patch('user', 'update')->name('api.user.update');
        Route::get('user', 'view')->name('api.user.view');
        Route::get('user/search-by-email', 'searchByEmail')->name('api.user.search');
        Route::get('user/devices', 'devices')->name('api.user.devices');
        Route::get('user/devices-transfers', 'transfers')->name('api.user.devices.transfers');
    });

    Route::controller(DeviceController::class)->group(function () {
        Route::get('devices/{device}', 'view')->name('api.device.view');
        Route::post('devices', 'register')->name('api.device.register');
        Route::delete('devices/{device}', 'delete')->name('api.device.delete');
    });

    Route::controller(DeviceValidationController::class)
        ->prefix('devices')
        ->name('api.device.validation.')
        ->group(function () {
            Route::post('{device}/validate', 'start')->name('start');
            Route::post('{device}/invalidate', 'invalidate')->name('invalidate');
        });

    Route::controller(DeviceTransferController::class)->group(function () {
        Route::post('devices/{device}', 'create')->name('api.device.transfer.create');
        Route::post('device-transfers/{deviceTransfer}/accept', 'accept')->name('api.device.transfer.accept');
        Route::post('device-transfers/{deviceTransfer}/cancel', 'cancel')->name('api.device.transfer.cancel');
        Route::post('device-transfers/{deviceTransfer}/reject', 'reject')->name('api.device.transfer.reject');
    });

    Route::controller(DeviceShareController::class)
        ->name('api.device.share.')
        ->group(function () {
            Route::post('devices/{device}/share', 'create')->name('create');
            Route::get('device/share', 'view')->name('view');
        });

    Route::controller(BrandController::class)->group(function () {
        Route::get('brands', 'brands');
    });

    Route::controller(DeviceModelController::class)->group(function () {
        Route::get('device-models/brands/{brand}', 'deviceModelsByBrand');
    });
});
