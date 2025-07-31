<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPenelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanPenelitianController extends Controller
{
    public function create()
    {
        return view('pengajuan.create_penelitian');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'judul_penelitian' => 'required|string|max:255',
            'metode' => 'required|in:Kuesioner,Wawancara,Lainnya',
            'surat_izin' => 'required|file|mimes:pdf|max:2048', // 2MB
            'proposal' => 'required|file|mimes:pdf|max:5120', // 5MB
            'daftar_pertanyaan' => 'required|file|mimes:pdf|max:2048', // 2MB
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg|max:1024', // 1MB
        ]);

        $suratIzinPath = $request->file('surat_izin')->store('penelitian/surat_izin', 'public');
        $proposalPath = $request->file('proposal')->store('penelitian/proposal', 'public');
        $daftarPertanyaanPath = $request->file('daftar_pertanyaan')->store('penelitian/daftar_pertanyaan', 'public');
        $ktpPath = $request->file('ktp')->store('penelitian/ktp', 'public');

        PengajuanPenelitian::create([
            'nama' => $request->nama,
            'instansi' => $request->instansi,
            'jurusan' => $request->jurusan,
            'judul_penelitian' => $request->judul_penelitian,
            'metode' => $request->metode,
            'surat_izin' => $suratIzinPath,
            'proposal' => $proposalPath,
            'daftar_pertanyaan' => $daftarPertanyaanPath,
            'ktp' => $ktpPath,
        ]);

        return redirect()->back()->with('success', 'Pengajuan penelitian berhasil disimpan!');
    }

    public function index(Request $request)
    {
        $query = PengajuanPenelitian::query();
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $penelitian = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.penelitian.index', compact('penelitian'));
    }

    public function show($id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        return view('admin.penelitian.show', compact('penelitian'));
    }

    public function updateStatus(Request $request, $id)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:Pengajuan,Diterima,Ditolak',
            'tanggal_pelaksanaan' => 'nullable|date',
            'keterangan_penolakan' => 'nullable|string',
            'surat_selesai' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $data = [
            'status' => $request->status,
        ];

        if ($request->status === 'Diterima' && $request->tanggal_pelaksanaan) {
            $data['tanggal_pelaksanaan'] = $request->tanggal_pelaksanaan;
        }

        if ($request->status === 'Ditolak' && $request->keterangan_penolakan) {
            $data['keterangan_penolakan'] = $request->keterangan_penolakan;
        }

        // Handle upload surat selesai
        if ($request->hasFile('surat_selesai')) {
            $data['surat_selesai'] = $request->file('surat_selesai')->store('surat_selesai_penelitian', 'public');
        }

        $penelitian->update($data);

        return redirect()->route('admin.penelitian.index')->with('success', 'Status penelitian berhasil diperbarui.');
    }

    public function downloadFile($id, $fileType)
    {
        $penelitian = PengajuanPenelitian::findOrFail($id);
        
        $filePath = null;
        $fileName = '';
        
        switch ($fileType) {
            case 'surat_izin':
                $filePath = $penelitian->surat_izin;
                $fileName = 'surat_izin_' . $penelitian->nama . '.pdf';
                break;
            case 'proposal':
                $filePath = $penelitian->proposal;
                $fileName = 'proposal_' . $penelitian->nama . '.pdf';
                break;
            case 'daftar_pertanyaan':
                $filePath = $penelitian->daftar_pertanyaan;
                $fileName = 'daftar_pertanyaan_' . $penelitian->nama . '.pdf';
                break;
            case 'ktp':
                $filePath = $penelitian->ktp;
                $fileName = 'ktp_' . $penelitian->nama . '.jpg';
                break;
            case 'surat_selesai':
                $filePath = $penelitian->surat_selesai;
                $fileName = 'surat_selesai_' . $penelitian->nama . '.pdf';
                break;
        }
        
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }
        
        return back()->with('error', 'File tidak ditemukan.');
    }

    public function generateLink()
    {
        $link = route('pengajuan.penelitian.create');
        return view('admin.penelitian.link', compact('link'));
    }
} 