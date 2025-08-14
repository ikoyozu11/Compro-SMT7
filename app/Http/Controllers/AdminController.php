<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penerimaan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function home() { return view('admin.home'); }
    public function user() { 
        $users = User::select('id','name','status','role')
            ->orderBy('status','desc')
            ->orderBy('name','asc')
            ->get();
        return view('admin.user', ['users' => $users]); 
    }
    public function pengajuanLink() { return redirect()->route('admin.form_links.index'); }
    public function pengajuanDaftar() { return view('admin.pengajuan_daftar'); }
    public function penerimaanLink() { return view('admin.penerimaan_link'); }
    public function penerimaanDaftar() { return view('admin.penerimaan_daftar'); }
    public function pelaksanaan() { return view('admin.pelaksanaan'); }
    public function hasil() { return view('admin.hasil'); }
    public function penelitianPengajuan() { return view('admin.penelitian_pengajuan'); }
    public function penelitianPenjadwalan() { return view('admin.penelitian_penjadwalan'); }
    public function peserta(Request $request)
    {
        $selectedLokasi = $request->get('lokasi', 'all');
        $selectedStatus = $request->get('status', 'all');
        $sort = $request->get('sort', 'desc');

        $query = Penerimaan::with(['pengajuan', 'lokasi']);

        // Filter lokasi
        if ($selectedLokasi !== 'all') {
            $query->where('lokasi_id', $selectedLokasi);
        }

        // Filter status periode magang
        if ($selectedStatus !== 'all') {
            $now = Carbon::now();
            if ($selectedStatus === 'aktif') {
                $query->whereDate('mulai_magang', '<=', $now)
                      ->whereDate('selesai_magang', '>=', $now);
            } elseif ($selectedStatus === 'selesai') {
                $query->whereDate('selesai_magang', '<', $now);
            }
        }

        // Sorting
        $peserta = $query->orderBy('created_at', $sort)->get();

        // Statistik
        $totalPeserta = Penerimaan::count();
        $now = Carbon::now();
        $sedangMagang = Penerimaan::whereDate('mulai_magang', '<=', $now)
                                   ->whereDate('selesai_magang', '>=', $now)
                                   ->count();
        $selesaiMagang = Penerimaan::whereDate('selesai_magang', '<', $now)->count();
        $lokasiAktif = Penerimaan::distinct('lokasi_id')->count('lokasi_id');

        // Data lokasi untuk filter
        $lokasi = Lokasi::all();

        return view('admin.peserta', compact(
            'peserta',
            'totalPeserta',
            'sedangMagang',
            'selesaiMagang',
            'lokasiAktif',
            'lokasi',
            'selectedLokasi',
            'selectedStatus'
        ));
    }
} 