<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Progress;

class ProgressController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        
        $progressList = Progress::where('user_id', $userId)
                              ->orderBy('created_at', 'desc')
                              ->get();
        
        return view('progress', ['progressList' => $progressList]);
    }
    
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'file' => 'required|file|mimes:pdf|max:2048', // 2MB max
            ]);
            
            $userId = Auth::user()->id;
            
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('progress_reports', $fileName, 'public');
                
                // Save to database
                $progress = new Progress();
                $progress->user_id = $userId;
                $progress->title = $request->title;
                $progress->description = $request->description;
                $progress->file_path = $filePath;
                $progress->file_name = $fileName;
                $progress->save();
                
                session()->flash('success', 'Laporan progress berhasil diupload.');
            } else {
                session()->flash('error', 'File tidak ditemukan.');
            }
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            session()->flash('error', 'File terlalu besar. Maksimal ukuran file adalah 2MB.');
        } catch (ValidationException $e) {
            throw $e; // biarkan Laravel menangani redirect dengan error bag
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat mengupload file: ' . $e->getMessage());
        }
        
        return redirect()->back();
    }
    
    public function download($id)
    {
        $userId = Auth::user()->id;
        $progress = Progress::where('id', $id)->where('user_id', $userId)->first();
        
        if (!$progress) {
            abort(404);
        }
        
        $filePath = storage_path('app/public/' . $progress->file_path);
        
        if (!file_exists($filePath)) {
            session()->flash('error', 'File tidak ditemukan.');
            return redirect()->back();
        }
        
        return response()->download($filePath, $progress->file_name);
    }
    
    public function delete($id)
    {
        $userId = Auth::user()->id;
        $progress = Progress::where('id', $id)->where('user_id', $userId)->first();
        
        if (!$progress) {
            abort(404);
        }
        
        // Delete file from storage (compatible with Storage::fake in tests)
        if (!empty($progress->file_path)) {
            Storage::disk('public')->delete($progress->file_path);
        }
        
        // Delete from database
        $progress->delete();
        
        session()->flash('success', 'Laporan progress berhasil dihapus.');
        return redirect()->back();
    }
}
