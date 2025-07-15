<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function home() { return view('admin.home'); }
    public function lokasi() { return view('admin.lokasi'); }
    public function user() { return view('admin.user'); }
    public function pengajuanLink() { return view('admin.pengajuan_link'); }
    public function pengajuanDaftar() { return view('admin.pengajuan_daftar'); }
    public function penerimaanLink() { return view('admin.penerimaan_link'); }
    public function penerimaanDaftar() { return view('admin.penerimaan_daftar'); }
    public function pelaksanaan() { return view('admin.pelaksanaan'); }
    public function hasil() { return view('admin.hasil'); }
    public function penelitianPengajuan() { return view('admin.penelitian_pengajuan'); }
    public function penelitianPenjadwalan() { return view('admin.penelitian_penjadwalan'); }
} 