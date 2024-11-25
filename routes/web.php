<?php

use App\Http\Controllers\BahanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
    // redirect ke halaman login jika belum login
    if (!Auth::check()) {
        return redirect('/login');
    }
    return view('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// middleware('auth') digunakan untuk membatasi akses ke halaman ini hanya untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::controller(RoleController::class)->prefix('role')->group(function () {
        Route::get('', 'index')->name('role.index');
        Route::get('data', 'data')->name('role.data');
        Route::post('store', 'store')->name('role.store');
        Route::put('update', 'update')->name('role.update');
        Route::delete('destroy', 'destroy')->name('role.destroy');
        Route::post('assign-permission', 'assignPermission')->name('role.assignPermission');
    });
    Route::controller(PermissionController::class)->prefix('permission')->group(function () {
        Route::get('', 'index')->name('permission.index');
        Route::get('data', 'data')->name('permission.data');
        Route::post('store', 'store')->name('permission.store');
        Route::put('update', 'update')->name('permission.update');
        Route::delete('destroy', 'destroy')->name('permission.destroy');
    });

    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('', 'index')->name('user.index');
        Route::get('data', 'data')->name('user.data');
        Route::post('store', 'store')->name('user.store');
        Route::put('update', 'update')->name('user.update');
        Route::delete('destroy', 'destroy')->name('user.destroy');
    });

    Route::controller(BahanController::class)->prefix('bahan')->group(function () {
        Route::get('', 'index')->name('bahan.index');
        Route::get('data', 'data')->name('bahan.data');
        Route::post('store', 'store')->name('bahan.store');
        Route::put('update', 'update')->name('bahan.update');
        Route::delete('destroy', 'destroy')->name('bahan.destroy');
    });

    Route::controller(SatuanController::class)->prefix('satuan')->group(function () {
        Route::get('', 'index')->name('satuan.index');
        Route::get('data', 'data')->name('satuan.data');
        Route::post('store', 'store')->name('satuan.store');
        Route::put('update', 'update')->name('satuan.update');
        Route::delete('destroy', 'destroy')->name('satuan.destroy');
        Route::post('restore', 'restore')->name('satuan.restore');
    });

    Route::controller(TukangController::class)->prefix('tukang')->group(function () {
        Route::get('', 'index')->name('tukang.index');
        Route::get('data', 'data')->name('tukang.data');
        Route::post('store', 'store')->name('tukang.store');
        Route::put('update', 'update')->name('tukang.update');
        Route::delete('destroy', 'destroy')->name('tukang.destroy');
    });
    
    Route::controller(ProjectController::class)->prefix('project')->group(function () {
        Route::get('', 'index')->name('project.index');
        Route::get('data', 'data')->name('project.data');
        Route::post('store', 'store')->name('project.store');
        Route::put('update', 'update')->name('project.update');
        Route::delete('destroy', 'destroy')->name('project.destroy');
    });
});
