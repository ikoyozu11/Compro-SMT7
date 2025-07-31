<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PengajuanMagangController;
use App\Http\Controllers\PengajuanPenelitianController;
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

/* AUTH */

Route::get('/login', function () { 

	if(Auth::check()){
		return redirect()->route('be.home');
	}

	return view('login'); 
})->name('login');

Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/* ADMIN */

Route::get('/be-home',function() { return view('home'); })->name('be.home')->middleware('auth');

Route::get('/be-um',[UserController::class, 'index'])->name('be.um')->middleware('auth');

Route::post('/be-um-add',[UserController::class, 'addUserSave'])->name('be.um.add')->middleware('auth');

Route::post('/be-um-edit/{id}', [UserController::class, 'editUserSave'])->name('be.um.edit')->middleware('auth');
Route::get('/be-um-edit-page/{id}', [UserController::class, 'editUserPage'])->name('be.um.edit.page')->middleware('auth');
Route::post('/be-um-delete/{id}', [UserController::class, 'deleteUser'])->name('be.um.delete')->middleware('auth');

/* MAGANG */

Route::get('mg-home', [AbsensiController::class, 'index'])->name('mg.home')->middleware('auth');

Route::post('mg-absen-save', [AbsensiController::class, 'saveAbsensi'])->name('mg.absen.save')->middleware('auth');

Route::get('mg-absen-history', [AbsensiController::class, 'history'])->name('mg.absen.history')->middleware('auth');

Route::get('/mg-recap/{start}/{end}', [AbsensiController::class, 'recap'])->name('mg.recap')->middleware('auth');

Route::post('mg-recap-m', [AbsensiController::class, 'recapMonthly'])->name('mg.recap.m')->middleware('auth');

/* SETTING */

Route::get('/set-pass',[SettingController::class, 'changePassword'])->name('set.password')->middleware('auth');

Route::post('/set-pass-save',[SettingController::class, 'changePasswordSave'])->name('set.password.save')->middleware('auth');

/* Admin routes */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [AdminController::class, 'home'])->name('home');

    Route::prefix('master')->name('master.')->group(function () {
        Route::get('/user', [AdminController::class, 'user'])->name('user');
        
        Route::get('lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
        Route::get('lokasi/create', [LokasiController::class, 'create'])->name('lokasi.create');
        Route::post('lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
        Route::get('lokasi/{id}/edit', [LokasiController::class, 'edit'])->name('lokasi.edit');
        Route::put('lokasi/{id}', [LokasiController::class, 'update'])->name('lokasi.update');
        Route::delete('lokasi/{id}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');
    });

    Route::get('/pengajuan/link', [AdminController::class, 'pengajuanLink'])->name('pengajuan.link');
    Route::get('/pengajuan/daftar', [App\Http\Controllers\PengajuanMagangController::class, 'daftarPengajuan'])->name('pengajuan.daftar');
    Route::get('/penerimaan/link', [AdminController::class, 'penerimaanLink'])->name('penerimaan.link');
    Route::get('/penerimaan/daftar', [PengajuanMagangController::class, 'index'])->name('penerimaan.daftar');
    Route::get('/penerimaan/{id}/edit', [PengajuanMagangController::class, 'edit'])->name('penerimaan.edit');
    Route::put('/penerimaan/{id}', [PengajuanMagangController::class, 'update'])->name('penerimaan.update');
    Route::post('/penerimaan/{id}/ubah-status', [PengajuanMagangController::class, 'ubahStatus'])->name('penerimaan.ubah-status');
    Route::delete('/penerimaan/{id}', [PengajuanMagangController::class, 'destroy'])->name('penerimaan.destroy');
    Route::get('/pelaksanaan', [AdminController::class, 'pelaksanaan'])->name('pelaksanaan');
    Route::get('/hasil', [AdminController::class, 'hasil'])->name('hasil');
    // Penelitian routes
    Route::get('/penelitian/link', [PengajuanPenelitianController::class, 'generateLink'])->name('penelitian.link');
    Route::get('/penelitian', [PengajuanPenelitianController::class, 'index'])->name('penelitian.index');
    Route::get('/penelitian/{id}', [PengajuanPenelitianController::class, 'show'])->name('penelitian.show');
    Route::post('/penelitian/{id}/update-status', [PengajuanPenelitianController::class, 'updateStatus'])->name('penelitian.update-status');
    Route::get('/penelitian/{id}/download/{fileType}', [PengajuanPenelitianController::class, 'downloadFile'])->name('penelitian.download');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('be.home');
    }
    return redirect()->route('login');
});

/* Pengajuan routes */
Route::get('/pengajuan', [PengajuanMagangController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanMagangController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan-magang', [PengajuanMagangController::class, 'create'])->name('pengajuan.create');
Route::get('/pengajuan-penelitian', [App\Http\Controllers\PengajuanPenelitianController::class, 'create'])->name('pengajuan.penelitian.create');
Route::post('/pengajuan-penelitian', [App\Http\Controllers\PengajuanPenelitianController::class, 'store'])->name('pengajuan.penelitian.store');