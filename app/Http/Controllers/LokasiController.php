<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        return view('lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('lokasi.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang' => 'required|string|max:128',
            'tim' => 'required|string|max:128',
            'quota' => 'required|integer',
            'alamat' => 'nullable|string',
        ]);
        Lokasi::create($request->all());
        return redirect()->route('admin.master.lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        return view('lokasi.form', compact('lokasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang' => 'required|string|max:128',
            'tim' => 'required|string|max:128',
            'quota' => 'required|integer',
            'alamat' => 'nullable|string',
        ]);
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update($request->all());
        return redirect()->route('admin.master.lokasi.index')->with('success', 'Lokasi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();
        return redirect()->route('admin.master.lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
} 