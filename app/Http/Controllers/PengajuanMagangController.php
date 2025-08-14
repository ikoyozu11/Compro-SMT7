<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanMagang::whereIn('status', ['diproses', 'diterima'])->orderBy('created_at', 'desc')->get();
        return view('penerimaan.index', compact('pengajuan'));
    }

    public function edit($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        return view('penerimaan.edit', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $data = $request->all();
        
        // Process group members data if provided
        if ($request->has('anggota_nama') && $request->has('anggota_hp')) {
            $anggotaNama = $request->input('anggota_nama', []);
            $anggotaHp = $request->input('anggota_hp', []);
            
            // Combine nama and hp into a structured format
            $namaAnggota = [];
            for ($i = 0; $i < count($anggotaNama); $i++) {
                if (!empty($anggotaNama[$i]) && !empty($anggotaHp[$i])) {
                    $namaAnggota[] = $anggotaNama[$i] . ' (HP: ' . $anggotaHp[$i] . ')';
                }
            }
            
            $data['nama_anggota'] = implode('; ', $namaAnggota);
        }
        
        // Handle file upload
        if ($request->hasFile('file_surat')) {
            $data['file_surat'] = $request->file('file_surat')->store('surat_penerimaan', 'public');
        }
        
        $pengajuan->update($data);
        return redirect()->route('admin.penerimaan.daftar')->with('success', 'Data pengajuan berhasil diupdate.');
    }



    public function ubahStatusPengajuan(Request $request, $id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        $status = $request->input('status');
        $catatan = $request->input('catatan');
        
        // Update the record
        $pengajuan->status = $status;
        $pengajuan->catatan = $catatan;
        $pengajuan->save();
        
        
        if ($status === 'diterima') {
            // Create user account if needed
            $user = User::where('username', $pengajuan->no_hp)->first();
            if (!$user) {
                User::create([
                    'username' => $pengajuan->no_hp,
                    'password' => Hash::make('magang123'),
                    'name' => $pengajuan->nama_pemohon,
                    'role' => 'magang',
                    'status' => 1,
                ]);
            }
            
            // Automatically create penerimaan record
            $this->createPenerimaanFromPengajuan($pengajuan);
        }
        
        return redirect()->route('admin.pengajuan.daftar')
            ->with('success', 'Status pengajuan berhasil diubah menjadi ' . ucfirst($status) . '.');
    }
    
    private function createPenerimaanFromPengajuan($pengajuan)
    {
        // Check if penerimaan already exists
        $existingPenerimaan = \App\Models\Penerimaan::where('pengajuan_id', $pengajuan->id)->first();
        
        if (!$existingPenerimaan) {
            \App\Models\Penerimaan::create([
                'pengajuan_id' => $pengajuan->id,
                'peserta_magang' => [
                    [
                        'nama' => $pengajuan->nama_pemohon,
                        'telepon' => $pengajuan->no_hp
                    ]
                ],
                'instansi_sekolah_universitas' => $pengajuan->asal_instansi ?? '',
                'jurusan' => $pengajuan->jurusan ?? '',
                'lokasi_id' => $pengajuan->lokasi_id,
                'mulai_magang' => $pengajuan->mulai_magang ?? now()->addDays(30),
                'selesai_magang' => $pengajuan->selesai_magang ?? now()->addDays(90),
                'status' => 'pending'
            ]);
        }
    }



    public function create()
    {
        // Ambil data lokasi untuk dropdown
        $lokasi = \App\Models\Lokasi::all();
        return view('pengajuan.create', compact('lokasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'anggota_nama' => 'required|array|min:1',
            'anggota_nama.*' => 'required|string|max:255',
            'anggota_hp' => 'required|array|min:1',
            'anggota_hp.*' => 'required|string|max:20',
            'asal_instansi' => 'required|string',
            'jurusan' => 'required|string',
            'keahlian' => 'required|string',
            'lokasi_id' => 'required|exists:lokasi,id',
            'mulai_magang' => 'required|date',
            'selesai_magang' => 'required|date|after_or_equal:mulai_magang',
        ]);

        // Process group members data
        $anggotaNama = $request->input('anggota_nama', []);
        $anggotaHp = $request->input('anggota_hp', []);
        
        // Combine nama and hp into a structured format
        $namaAnggota = [];
        for ($i = 0; $i < count($anggotaNama); $i++) {
            if (!empty($anggotaNama[$i]) && !empty($anggotaHp[$i])) {
                $namaAnggota[] = $anggotaNama[$i] . ' (HP: ' . $anggotaHp[$i] . ')';
            }
        }
        
        $data = $request->all();
        $data['status'] = 'pengajuan';
        $data['nama_anggota'] = implode('; ', $namaAnggota);
        
        // If form_link_id is provided, validate it exists and is active
        if ($request->has('form_link_id')) {
            $formLink = \App\Models\FormLink::find($request->form_link_id);
            if (!$formLink || !$formLink->isActive()) {
                return redirect()->back()->withErrors(['form_link' => 'Form link tidak valid atau sudah tidak aktif.']);
            }
        }
        
        PengajuanMagang::create($data);
        return redirect()->back()->with('success', 'Pengajuan magang berhasil dikirim!');
    }

    public function daftarPengajuan(Request $request)
    {
        $status = $request->get('status', 'all');
        $sort = $request->get('sort', 'desc'); // Ambil parameter sort
        $query = PengajuanMagang::query();
        
        // DEBUG: Let's see what's actually in the database
        $allApplications = PengajuanMagang::all();
        \Log::info('All applications in database:', $allApplications->toArray());
        
        // Hanya tampilkan pengajuan, diproses, ditolak
        if ($status !== 'all') {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pengajuan', 'diproses', 'ditolak']);
        }
        
        // Terapkan sorting berdasarkan parameter sort
        $pengajuan = $query->orderBy('created_at', $sort)->get();
        
        // DEBUG: Let's see what the query returns
        \Log::info('Query status: ' . $status);
        \Log::info('Query SQL: ' . $query->toSql());
        \Log::info('Query results:', $pengajuan->toArray());

        // Hitung keterisian kuota lokasi berdasarkan anggota, hanya status 'diterima'
        $lokasi = \App\Models\Lokasi::all();
        $kuota = [];
        foreach ($lokasi as $l) {
            $terisi = PengajuanMagang::where('lokasi_id', $l->id)
                ->where('status', 'diterima')
                ->get()
                ->sum(function($p) {
                    return 1 + count(array_filter(array_map('trim', explode(';', $p->nama_anggota))));
                });
            $kuota[] = [
                'id' => $l->id,
                'bidang' => $l->bidang,
                'tim' => $l->tim,
                'quota' => $l->quota,
                'terisi' => $terisi,
            ];
        }
        // DEBUG: Add debug info to the view
        $debugInfo = [
            'total_applications' => $allApplications->count(),
            'filter_status' => $status,
            'query_results_count' => $pengajuan->count(),
            'all_statuses' => $allApplications->pluck('status')->toArray()
        ];
        
        return view('admin.pengajuan_daftar', compact('pengajuan', 'kuota', 'status', 'debugInfo'));
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        if ($pengajuan->status !== 'ditolak') {
            return redirect()->back()->with('error', 'Hanya pengajuan yang ditolak yang bisa dihapus.');
        }
        $pengajuan->delete();
        return redirect()->route('admin.penerimaan.daftar')->with('success', 'Pengajuan magang berhasil dihapus.');
    }
}
