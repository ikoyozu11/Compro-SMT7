<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PengajuanMagangController;

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
    Route::get('/master/lokasi', [AdminController::class, 'lokasi'])->name('lokasi');
    Route::get('/master/user', [AdminController::class, 'user'])->name('user');
    Route::get('/pengajuan/link', [AdminController::class, 'pengajuanLink'])->name('pengajuan.link');
    Route::get('/pengajuan/daftar', [AdminController::class, 'pengajuanDaftar'])->name('pengajuan.daftar');
    Route::get('/penerimaan/link', [AdminController::class, 'penerimaanLink'])->name('penerimaan.link');
    Route::get('/penerimaan/daftar', [AdminController::class, 'penerimaanDaftar'])->name('penerimaan.daftar');
    Route::get('/pelaksanaan', [AdminController::class, 'pelaksanaan'])->name('pelaksanaan');
    Route::get('/hasil', [AdminController::class, 'hasil'])->name('hasil');
    Route::get('/penelitian/pengajuan', [AdminController::class, 'penelitianPengajuan'])->name('penelitian.pengajuan');
    Route::get('/penelitian/penjadwalan', [AdminController::class, 'penelitianPenjadwalan'])->name('penelitian.penjadwalan');
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