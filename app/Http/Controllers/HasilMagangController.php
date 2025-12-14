<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\HasilMagang;
use App\Models\Penerimaan;
use Carbon\Carbon;

class HasilMagangController extends Controller
{
    public function index()
    {
        // Get all penerimaan records that have status 'approved' (completed internships)
        $hasilMagang = HasilMagang::with(['penerimaan.pengajuan', 'penerimaan.lokasi'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Also get penerimaan records that are approved but don't have hasil magang yet
        $pendingPenerimaan = Penerimaan::where('status', 'approved')
            ->whereDoesntHave('hasilMagang')
            ->with(['pengajuan', 'lokasi'])
            ->get();

        return view('admin.hasil', compact('hasilMagang', 'pendingPenerimaan'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'penerimaan_id' => 'required|exists:penerimaan,id',
            'laporan_hasil_magang' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'catatan' => 'nullable|string',
        ]);

        $data = [
            'penerimaan_id' => $request->penerimaan_id,
            'catatan' => $request->catatan,
            'status' => 'pending',
            'tanggal_selesai' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('laporan_hasil_magang')) {
            $data['laporan_hasil_magang'] = $request->file('laporan_hasil_magang')->store('laporan_hasil_magang', 'public');
        }

        HasilMagang::create($data);

        return redirect()->route('admin.hasil')->with('success', 'Laporan hasil magang berhasil diupload.');
    }

    public function uploadSuratKeterangan(Request $request, $id)
    {
        $request->validate([
            'surat_keterangan_selesai' => 'required|file|mimes:pdf|max:2048', // 2MB max
            'catatan' => 'nullable|string',
        ]);

        $hasilMagang = HasilMagang::findOrFail($id);

        $data = [
            'catatan' => $request->catatan,
            'status' => 'completed',
            'tanggal_selesai' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('surat_keterangan_selesai')) {
            // Delete old file if exists
            if ($hasilMagang->surat_keterangan_selesai) {
                Storage::disk('public')->delete($hasilMagang->surat_keterangan_selesai);
            }
            $data['surat_keterangan_selesai'] = $request->file('surat_keterangan_selesai')->store('surat_keterangan_selesai', 'public');
        }

        $hasilMagang->update($data);

        return redirect()->route('admin.hasil')->with('success', 'Surat keterangan selesai magang berhasil diupload.');
    }

    public function downloadLaporan($id)
    {
        $hasilMagang = HasilMagang::findOrFail($id);
        
        if (!$hasilMagang->laporan_hasil_magang) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return Storage::disk('public')->download($hasilMagang->laporan_hasil_magang);
    }

    public function downloadSuratKeterangan($id)
    {
        $hasilMagang = HasilMagang::findOrFail($id);
        
        if (!$hasilMagang->surat_keterangan_selesai) {
            return back()->with('error', 'File surat keterangan tidak ditemukan.');
        }

        return Storage::disk('public')->download($hasilMagang->surat_keterangan_selesai);
    }

    public function destroy($id)
    {
        $hasilMagang = HasilMagang::findOrFail($id);

        // Delete associated files
        if ($hasilMagang->laporan_hasil_magang) {
            Storage::disk('public')->delete($hasilMagang->laporan_hasil_magang);
        }
        if ($hasilMagang->surat_keterangan_selesai) {
            Storage::disk('public')->delete($hasilMagang->surat_keterangan_selesai);
        }

        $hasilMagang->delete();

        return redirect()->route('admin.hasil')->with('success', 'Data hasil magang berhasil dihapus.');
    }

    // Methods for magang users
    public function magangIndex()
    {
        $user = Auth::user();
        
        // Find penerimaan record by matching user with pengajuan or peserta_magang (JSON)
        $penerimaan = Penerimaan::where(function($q) use ($user) {
                $q->whereHas('pengajuan', function($query) use ($user) {
                    $query->where('no_hp', $user->phone)
                          ->orWhere('nama_pemohon', $user->name);
                });
                if (\DB::connection()->getDriverName() === 'sqlite') {
                    // Fallback: search JSON text with LIKE for SQLite
                    $q->orWhere('peserta_magang', 'like', '%'.($user->phone ?? '').'%')
                      ->orWhere('peserta_magang', 'like', '%'.($user->name ?? '').'%');
                } else {
                    // MySQL JSON search
                    $q->orWhereRaw("JSON_SEARCH(peserta_magang, 'one', ?) IS NOT NULL", [$user->phone])
                      ->orWhereRaw("JSON_SEARCH(peserta_magang, 'one', ?) IS NOT NULL", [$user->name]);
                }
            })
            ->with(['pengajuan', 'lokasi', 'hasilMagang'])
            ->first();

        if (!$penerimaan) {
            return view('magang.hasil-magang', compact('penerimaan'))
                ->with('error', 'Anda belum memiliki data penerimaan magang.');
        }

        return view('magang.hasil-magang', compact('penerimaan'));
    }

    public function magangStore(Request $request)
    {
        $user = Auth::user();
        
        // Get penerimaan record for this user (match pengajuan or peserta_magang JSON)
        $penerimaan = Penerimaan::where(function($q) use ($user) {
                $q->whereHas('pengajuan', function($query) use ($user) {
                    $query->where('no_hp', $user->phone)
                          ->orWhere('nama_pemohon', $user->name);
                });
                if (\DB::connection()->getDriverName() === 'sqlite') {
                    $q->orWhere('peserta_magang', 'like', '%'.($user->phone ?? '').'%')
                      ->orWhere('peserta_magang', 'like', '%'.($user->name ?? '').'%');
                } else {
                    $q->orWhereRaw("JSON_SEARCH(peserta_magang, 'one', ?) IS NOT NULL", [$user->phone])
                      ->orWhereRaw("JSON_SEARCH(peserta_magang, 'one', ?) IS NOT NULL", [$user->name]);
                }
            })
            ->first();

        if (!$penerimaan) {
            return back()->with('error', 'Anda belum memiliki data penerimaan magang.');
        }

        // Check if user already has hasil magang
        if ($penerimaan->hasilMagang) {
            return back()->with('error', 'Anda sudah mengupload laporan hasil magang.');
        }

        $request->validate([
            'laporan_hasil_magang' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'catatan' => 'nullable|string',
        ]);

        $data = [
            'penerimaan_id' => $penerimaan->id,
            'catatan' => $request->catatan,
            'status' => 'pending',
            'tanggal_selesai' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('laporan_hasil_magang')) {
            $data['laporan_hasil_magang'] = $request->file('laporan_hasil_magang')->store('laporan_hasil_magang', 'public');
        }

        HasilMagang::create($data);

        return redirect()->route('mg.hasil')->with('success', 'Laporan hasil magang berhasil diupload.');
    }

    public function magangUploadSuratKeterangan(Request $request)
    {
        $user = Auth::user();
        
        // Get penerimaan record for this user (match pengajuan or peserta_magang JSON)
        $penerimaan = Penerimaan::where(function($q) use ($user) {
                $q->whereHas('pengajuan', function($query) use ($user) {
                    $query->where('no_hp', $user->phone)
                          ->orWhere('nama_pemohon', $user->name);
                });
                if (\DB::connection()->getDriverName() === 'sqlite') {
                    $q->orWhere('peserta_magang', 'like', '%'.($user->phone ?? '').'%')
                      ->orWhere('peserta_magang', 'like', '%'.($user->name ?? '').'%');
                } else {
                    $q->orWhereRaw("JSON_SEARCH(peserta_magang, 'one', ?) IS NOT NULL", [$user->phone])
                      ->orWhereRaw("JSON_SEARCH(peserta_magang, 'one', ?) IS NOT NULL", [$user->name]);
                }
            })
            ->with('hasilMagang')
            ->first();

        if (!$penerimaan || !$penerimaan->hasilMagang) {
            return back()->with('error', 'Anda belum mengupload laporan hasil magang.');
        }

        $request->validate([
            'surat_keterangan_selesai' => 'required|file|mimes:pdf|max:2048', // 2MB max
            'catatan' => 'nullable|string',
        ]);

        $hasilMagang = $penerimaan->hasilMagang;

        $data = [
            'catatan' => $request->catatan,
            'status' => 'completed',
            'tanggal_selesai' => now(),
        ];

        // Handle file upload
        if ($request->hasFile('surat_keterangan_selesai')) {
            // Delete old file if exists
            if ($hasilMagang->surat_keterangan_selesai) {
                Storage::disk('public')->delete($hasilMagang->surat_keterangan_selesai);
            }
            $data['surat_keterangan_selesai'] = $request->file('surat_keterangan_selesai')->store('surat_keterangan_selesai', 'public');
        }

        $hasilMagang->update($data);

        return redirect()->route('mg.hasil')->with('success', 'Surat keterangan selesai magang berhasil diupload.');
    }
}
