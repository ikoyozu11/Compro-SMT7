<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\LokasiMagang;
use Illuminate\Http\Request;

class PengajuanMagangController extends Controller
{
    public function create()
    {
        $lokasi = LokasiMagang::all();
        return view('pengajuan.create', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon'    => 'required|string|max:255',
            'no_hp'           => 'required|string|max:20',
            'nama_anggota'    => 'required|string',
            'asal_instansi'   => 'required|string',
            'jurusan'         => 'required|string',
            'keahlian'        => 'required|string',
            'lokasi_id'       => 'required|exists:lokasi_magang,id',
            'mulai_magang'    => 'required|date',
            'selesai_magang'  => 'required|date|after_or_equal:mulai_magang',
        ]);

        PengajuanMagang::create($request->all());

        return redirect()->back()->with('success', 'Pengajuan berhasil disimpan!');
    }
}
