<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthentikasiController;
use App\Http\Controllers\Admin\ReportControllerAdmin;
use App\Http\Controllers\ReportCommentController;
use App\Http\Controllers\Admin\UserVerificationControllerAdmin;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthentikasiController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthentikasiController::class, 'login']);
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
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */
    Route::resource('laporan', ReportControllerAdmin::class)
        ->except(['edit', 'update', 'destroy']);

    Route::get('laporan-masuk', [ReportControllerAdmin::class, 'laporan'])
        ->name('laporan.masuk');

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
    Route::post('laporan/{report}/komentar', [ReportCommentController::class, 'store'])
        ->name('laporan.komentar.store');
});
