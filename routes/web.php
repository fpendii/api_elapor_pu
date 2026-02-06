<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthentikasiController;
use App\Http\Controllers\Admin\ReportControllerAdmin;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\Admin\DashboardControllerAdmin;
use App\Http\Controllers\Admin\UserVerificationControllerAdmin;
use App\Http\Controllers\Admin\ProfileControllerAdmin;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

// Route::get('/', fn() => view('welcome'));

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/login', [AuthentikasiController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthentikasiController::class, 'login']);

// Routes Register
Route::get('/register', [AuthentikasiController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthentikasiController::class, 'register']);

Route::post('/logout', [AuthentikasiController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    /*

*/
    Route::get('/dashboard', [DashboardControllerAdmin::class, 'index'])->name('dashboard');
    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */
    Route::resource('laporan', ReportControllerAdmin::class)
        ->except(['edit', 'update', 'destroy']);
    /*
    |--------------------------------------------------------------------------
    | Status Actions
    |--------------------------------------------------------------------------
    */
    Route::post('laporan/{report}/terima', [ReportControllerAdmin::class, 'terima'])
        ->name('laporan.terima');

    Route::post('laporan/{report}/tolak', [ReportControllerAdmin::class, 'tolak'])
        ->name('laporan.tolak');

    Route::post('laporan/{report}/selesai', [ReportControllerAdmin::class, 'selesai'])
        ->name('laporan.selesai');

    /*
    |--------------------------------------------------------------------------
    | Manajemen User
    |--------------------------------------------------------------------------
    */
    Route::resource('users', App\Http\Controllers\Admin\UserControllerAdmin::class)
        ->only(['index', 'store', 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Export Laporan
    |--------------------------------------------------------------------------
    */
    Route::get('/laporan/export/{status}', [App\Http\Controllers\Admin\LaporanExportController::class, 'excel'])
        ->name('laporan.export.excel');

    Route::get('/export/pdf/{status}', [App\Http\Controllers\Admin\LaporanExportController::class, 'pdf'])
        ->name('laporan.export.pdf');

    /*
    |--------------------------------------------------------------------------
    | Verifikasi User
    |--------------------------------------------------------------------------
    */
    Route::get('users/verifikasi', [UserVerificationControllerAdmin::class, 'index'])
        ->name('users.verifikasi.index');

    Route::get('users/verifikasi/{user}', [UserVerificationControllerAdmin::class, 'show'])
        ->name('users.verifikasi.show');

    Route::post('users/verifikasi/{user}/acc', [UserVerificationControllerAdmin::class, 'acc'])
        ->name('users.verifikasi.acc');

    Route::post('users/verifikasi/{user}/tolak', [UserVerificationControllerAdmin::class, 'tolak'])
        ->name('users.verifikasi.tolak');

    /*
    |--------------------------------------------------------------------------
    | Komentar Laporan
    |--------------------------------------------------------------------------
    */
    Route::post('laporan/{id}/komentar', [ReportCommentController::class, 'store'])
        ->name('laporan.komentar.store');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/profile', [ProfileControllerAdmin::class, 'index'])->name('profile.index');
    Route::get('/admin/profile/edit', [ProfileControllerAdmin::class, 'edit'])->name('profile.edit');
    Route::put('/admin/profile', [ProfileControllerAdmin::class, 'update'])->name('profile.update');


    /*|--------------------------------------------------------------------------
    | Ujicoba Cloud Vision API
    |--------------------------------------------------------------------------
    */
    Route::get('/cloud-vision/test', [App\Http\Controllers\Ujicoba\CloudVisionController::class, 'test'])->name('cloud-vision.test');
});
