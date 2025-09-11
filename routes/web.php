<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PengajuanMagangController;
use App\Http\Controllers\PengajuanPenelitianController;
use App\Http\Controllers\FormLinkController;
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
		return redirect()->route('admin.home');
	}

	return view('login'); 
})->name('login');

Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/* ADMIN */





/* MAGANG */

Route::get('mg-home', [AbsensiController::class, 'index'])->name('mg.home')->middleware('auth');

Route::post('mg-absen-save', [AbsensiController::class, 'saveAbsensi'])->name('mg.absen.save')->middleware('auth');

Route::post('mg-absen-masuk', [AbsensiController::class, 'absenMasuk'])->name('mg.absen.masuk')->middleware('auth');

Route::post('mg-absen-pulang', [AbsensiController::class, 'absenPulang'])->name('mg.absen.pulang')->middleware('auth');

Route::get('mg-absen-history', [AbsensiController::class, 'history'])->name('mg.absen.history')->middleware('auth');

Route::get('mg-progress', [ProgressController::class, 'index'])->name('mg.progress')->middleware('auth');

Route::post('mg-progress-upload', [ProgressController::class, 'upload'])->name('mg.progress.upload')->middleware(['auth', 'handle.post.too.large']);

Route::get('mg-progress-download/{id}', [ProgressController::class, 'download'])->name('mg.progress.download')->middleware('auth');

Route::delete('mg-progress-delete/{id}', [ProgressController::class, 'delete'])->name('mg.progress.delete')->middleware('auth');

Route::get('mg-hasil', [App\Http\Controllers\HasilMagangController::class, 'magangIndex'])->name('mg.hasil')->middleware('auth');
Route::post('mg-hasil', [App\Http\Controllers\HasilMagangController::class, 'magangStore'])->name('mg.hasil.store')->middleware('auth');
Route::post('mg-hasil-upload-surat', [App\Http\Controllers\HasilMagangController::class, 'magangUploadSuratKeterangan'])->name('mg.hasil.upload-surat')->middleware('auth');


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
        Route::get('/user/add', [AdminController::class, 'user'])->name('user.add');
        Route::post('/user/add', [UserController::class, 'addUserSave'])->name('user.add.save');
        Route::post('/user/edit/{id}', [UserController::class, 'editUserSave'])->name('user.edit');
        Route::get('/user/edit-page/{id}', [UserController::class, 'editUserPage'])->name('user.edit-page');
        Route::post('/user/delete/{id}', [UserController::class, 'deleteUser'])->name('user.delete');
        
        Route::get('lokasi', [LokasiController::class, 'index'])->name('lokasi.index');
        Route::get('lokasi/create', [LokasiController::class, 'create'])->name('lokasi.create');
        Route::post('lokasi', [LokasiController::class, 'store'])->name('lokasi.store');
        Route::get('lokasi/{id}/edit', [LokasiController::class, 'edit'])->name('lokasi.edit');
        Route::put('lokasi/{id}', [LokasiController::class, 'update'])->name('lokasi.update');
        Route::delete('lokasi/{id}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');
    });

    Route::get('/pengajuan/link', [FormLinkController::class, 'index'])->name('pengajuan.link');
    Route::get('/form-links', [FormLinkController::class, 'index'])->name('form_links.index');
    Route::get('/form-links/create', [FormLinkController::class, 'create'])->name('form_links.create');
    Route::post('/form-links', [FormLinkController::class, 'store'])->name('form_links.store');
    Route::delete('/form-links/{id}', [FormLinkController::class, 'destroy'])->name('form_links.destroy');
    Route::post('/form-links/{id}/toggle-status', [FormLinkController::class, 'toggleStatus'])->name('form_links.toggle-status');
    Route::get('/pengajuan/daftar', [App\Http\Controllers\PengajuanMagangController::class, 'daftarPengajuan'])->name('pengajuan.daftar');
    Route::post('/pengajuan/{id}/ubah-status', [PengajuanMagangController::class, 'ubahStatusPengajuan'])->name('pengajuan.ubah-status');
    Route::post('/pengajuan/{id}/generate-link', [PengajuanMagangController::class, 'generatePenerimaanLink'])->name('pengajuan.generate-link');
    
    // Penerimaan routes
    Route::get('/penerimaan', [App\Http\Controllers\PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::get('/penerimaan/create', [App\Http\Controllers\PenerimaanController::class, 'create'])->name('penerimaan.create');
    Route::post('/penerimaan', [App\Http\Controllers\PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}', [App\Http\Controllers\PenerimaanController::class, 'show'])->name('penerimaan.show');
    Route::get('/penerimaan/{id}/edit', [App\Http\Controllers\PenerimaanController::class, 'edit'])->name('penerimaan.edit');
    Route::put('/penerimaan/{id}', [App\Http\Controllers\PenerimaanController::class, 'update'])->name('penerimaan.update');
    Route::delete('/penerimaan/{id}', [App\Http\Controllers\PenerimaanController::class, 'destroy'])->name('penerimaan.destroy');
    Route::post('/penerimaan/{id}/update-status', [App\Http\Controllers\PenerimaanController::class, 'updateStatus'])->name('penerimaan.update-status');

    // Peserta
    Route::get('/peserta', [AdminController::class, 'peserta'])->name('peserta');
    Route::post('/pelaksanaan/{id}/selesai', [AdminController::class, 'selesaiMagang'])->name('pelaksanaan.selesai');

    Route::get('/pelaksanaan', [AdminController::class, 'pelaksanaan'])->name('pelaksanaan');
    Route::get('/hasil', [App\Http\Controllers\HasilMagangController::class, 'index'])->name('hasil');
    Route::post('/hasil', [App\Http\Controllers\HasilMagangController::class, 'store'])->name('hasil.store');
    Route::post('/hasil/{id}/upload-surat-keterangan', [App\Http\Controllers\HasilMagangController::class, 'uploadSuratKeterangan'])->name('hasil.upload-surat-keterangan');
    Route::get('/hasil/{id}/download-laporan', [App\Http\Controllers\HasilMagangController::class, 'downloadLaporan'])->name('hasil.download-laporan');
    Route::get('/hasil/{id}/download-surat-keterangan', [App\Http\Controllers\HasilMagangController::class, 'downloadSuratKeterangan'])->name('hasil.download-surat-keterangan');
    
    // Additional routes for admin view compatibility
    Route::get('/hasil/{id}/download-laporan', [App\Http\Controllers\HasilMagangController::class, 'downloadLaporan'])->name('hasil.download.laporan');
    Route::get('/hasil/{id}/download-surat-keterangan', [App\Http\Controllers\HasilMagangController::class, 'downloadSuratKeterangan'])->name('hasil.download.surat');
    Route::post('/hasil', [App\Http\Controllers\HasilMagangController::class, 'store'])->name('hasil.store');
    Route::post('/hasil/{id}/upload-surat-keterangan', [App\Http\Controllers\HasilMagangController::class, 'uploadSuratKeterangan'])->name('hasil.upload-surat-keterangan');
    Route::delete('/hasil/{id}', [App\Http\Controllers\HasilMagangController::class, 'destroy'])->name('hasil.destroy');
    // Penelitian routes
    Route::get('/penelitian/link', [PengajuanPenelitianController::class, 'generateLink'])->name('penelitian.link');
    Route::get('/penelitian/create-link', [PengajuanPenelitianController::class, 'createFormLink'])->name('penelitian.create-link');
    Route::post('/penelitian/store-link', [PengajuanPenelitianController::class, 'storeFormLink'])->name('penelitian.store-link');
    Route::delete('/penelitian/destroy-link/{id}', [PengajuanPenelitianController::class, 'destroyFormLink'])->name('penelitian.destroy-link');
    Route::post('/penelitian/toggle-status-link/{id}', [PengajuanPenelitianController::class, 'toggleStatusFormLink'])->name('penelitian.toggle-status-link');
    Route::get('/penelitian', [PengajuanPenelitianController::class, 'index'])->name('penelitian.index');
    Route::get('/penelitian/{id}', [PengajuanPenelitianController::class, 'show'])->name('penelitian.show');
    Route::post('/penelitian/{id}/update-status', [PengajuanPenelitianController::class, 'updateStatus'])->name('penelitian.update-status');
    Route::get('/penelitian/{id}/download/{fileType}', [PengajuanPenelitianController::class, 'downloadFile'])->name('penelitian.download');
});

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('mg.home');
        }
    }
    return redirect()->route('login');
});

/* Pengajuan routes */
Route::get('/pengajuan', [PengajuanMagangController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanMagangController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan-magang', [PengajuanMagangController::class, 'create'])->name('pengajuan.create');
Route::get('/pengajuan-penelitian', [App\Http\Controllers\PengajuanPenelitianController::class, 'create'])->name('pengajuan.penelitian.create');
Route::post('/pengajuan-penelitian', [App\Http\Controllers\PengajuanPenelitianController::class, 'store'])->name('pengajuan.penelitian.store');

/* Form Links */
Route::get('/form/{token}', [FormLinkController::class, 'show'])->name('form.show');

/* Penerimaan Form Routes */
Route::get('/penerimaan-form/{token}', [PengajuanMagangController::class, 'showPenerimaanForm'])->name('penerimaan.form.show');
Route::post('/penerimaan-form', [PengajuanMagangController::class, 'storePenerimaan'])->name('penerimaan.store');