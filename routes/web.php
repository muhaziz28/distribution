<?php

use App\Http\Controllers\AuthOtpController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\BlockMaterialDistributionController;
use App\Http\Controllers\BlockTukangDistributionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DetailProjectController;
use App\Http\Controllers\DistirbutionController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionMaterialController;
use App\Http\Controllers\TukangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WorkerPaymentController;
use App\Models\WorkerPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use League\CommonMark\Parser\Block\BlockContinue;
use Spatie\Permission\Contracts\Role;

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

// Auth::routes();
Route::get('login', [AuthOtpController::class, 'login'])->name('login');
Route::controller(AuthOtpController::class)->prefix("otp")->group(function () {
    Route::post('/generate', 'generate')->name('otp.generate');
    Route::get('/verification/{user_id}', 'verification')->name('otp.verification');
    Route::post('/login', 'loginWithOtp')->name('otp.getlogin');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthOtpController::class, 'logout'])->name('logout');
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
        Route::get('worker', 'dataForWorker')->name('tukang.dataForWorker');
    });


    Route::controller(ProjectController::class)->prefix('project')->group(function () {
        Route::get('', 'index')->name('project.index');
        Route::get('data', 'data')->name('project.data');
        Route::post('store', 'store')->name('project.store');
        Route::put('update', 'update')->name('project.update');
        Route::delete('destroy', 'destroy')->name('project.destroy');
    });

    Route::controller(VendorController::class)->prefix('vendor')->group(function () {
        Route::get('', 'index')->name('vendor.index');
        Route::get('data', 'data')->name('vendor.data');
        Route::post('store', 'store')->name('vendor.store');
        Route::put('update', 'update')->name('vendor.update');
        Route::delete('destroy', 'destroy')->name('vendor.destroy');
    });

    Route::controller(DetailProjectController::class)->prefix('detail-project')->group(function () {
        Route::get('/{id}', 'index')->name('project.detail');
        Route::get('/material-purchases-data/{id}', 'materialPurchasesData')->name('project.materialPurchasesData');
        Route::get('/worker-assignment-data/{id}', 'workerAssignmentData')->name('project.workerAssignmentData');
        Route::post('addDetail/{id}', 'addDetail')->name('project.addDetail');
        Route::delete('hapusDetail', 'hapusDetail')->name('project.hapusDetail');
    });

    Route::controller(TransactionMaterialController::class)->prefix('transaction-materials')->group(function () {
        Route::get('', 'index')->name('transaction-materials.index');
        Route::post('/store', 'store')->name('transaction-materials.store');
        Route::get('/transaction-detail/{materialPurchasesID}', 'detailTransaction')->name('transaction-materials.detailTransaction');
    });

    Route::controller(WorkerPaymentController::class)->prefix("worker-payment")->group(function () {
        Route::get('/{projectID}', 'data')->name("worker-payment.data");
        Route::delete('destroy', 'destroy')->name("worker-payment.destroy");
        Route::post('store', 'store')->name("worker-payment.store");
    });

    Route::post('uploads/process', [FileUploadController::class, 'process'])->name('uploads.process');
    Route::post('save', [FileUploadController::class, 'save'])->name('uploads.save');
    Route::delete('/uploads/revert', [FileUploadController::class, 'revert'])->name('uploads.revert');

    Route::controller(BlockController::class)->prefix('block')->group(function () {
        Route::get('data/{id}', 'data')->name('block.data');
        Route::post('store/{id}', 'store')->name('block.store');
        Route::put('update', 'update')->name('block.update');
        Route::delete('destroy', 'destroy')->name('block.destroy');

        Route::get('detail/{id}', 'detail')->name('block.detail');
    });

    Route::controller(CustomerController::class)->prefix('customer')->group(function () {
        Route::get('', 'index')->name('customer.index');
        Route::get('data', 'data')->name('customer.data');
        Route::post('store', 'store')->name('customer.store');
        Route::put('update', 'update')->name('customer.update');
        Route::delete('destroy', 'destroy')->name('customer.destroy');
    });

    Route::controller(MaterialController::class)->prefix('material')->group(function () {
        Route::get('', 'index')->name('material.index');
        Route::get('data', 'data')->name('material.data');
    });

    Route::controller(TransactionController::class)->prefix('transaction')->group(function () {
        Route::get('', 'index')->name('transaction.index');
        Route::get('data', 'data')->name('transaction.data');
    });

    Route::controller(DistirbutionController::class)->prefix('distribution')->group(function () {
        Route::post('', 'distribute')->name('distribution.distribute');
    });

    Route::controller(BlockMaterialDistributionController::class)->prefix('block-material')->group(function () {
        Route::get('/{blockID}', 'data')->name('block-material.data');
    });

    Route::controller(BlockTukangDistributionController::class)->prefix('block-tukang')->group(function () {
        Route::get('/{blockTukangId}', 'data')->name('block-tukang.data');
        Route::post('/{blockID}', 'store')->name('block-tukang.store');
        Route::delete('destroy', 'destroy')->name('block-tukang.destroy');
    });

    

    Route::post('/{id}', [ReturnController::class, 'return'])->name('return');
});
