<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use Illuminate\Http\Request;

class AplikasiController extends Controller
{
    /**
     * Tampilkan semua data aplikasi (diurutkan ASC berdasarkan nama_aplikasi).
     */
    public function index()
    {
        $aplikasis = Aplikasi::orderBy('nama_aplikasi', 'asc')->get();
        return view('admin.aplikasi.index', compact('aplikasis'));
    }

    /**
     * Tampilkan form tambah aplikasi.
     */
    public function create()
    {
        return view('admin.aplikasi.create');
    }

    /**
     * Simpan aplikasi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_aplikasi' => 'required|string|max:255|unique:aplikasis,nama_aplikasi',
        ]);

        Aplikasi::create([
            'nama_aplikasi' => $request->nama_aplikasi,
        ]);

        return redirect()->route('admin.aplikasi.index')->with('success', 'Aplikasi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit aplikasi.
     */
    public function edit($id)
    {
        $aplikasi = Aplikasi::findOrFail($id);
        return view('admin.aplikasi.edit', compact('aplikasi'));
    }

    /**
     * Update data aplikasi.
     */
    public function update(Request $request, $id)
    {
        $aplikasi = Aplikasi::findOrFail($id);

        $request->validate([
            'nama_aplikasi' => 'required|string|max:255|unique:aplikasis,nama_aplikasi,' . $aplikasi->id,
        ]);

        $aplikasi->update([
            'nama_aplikasi' => $request->nama_aplikasi,
        ]);

        return redirect()->route('admin.aplikasi.index')->with('success', 'Aplikasi berhasil diperbarui.');
    }

    /**
     * Hapus data aplikasi.
     */
    public function destroy($id)
    {
        $aplikasi = Aplikasi::findOrFail($id);
        $aplikasi->delete();

        return redirect()->route('admin.aplikasi.index')->with('success', 'Aplikasi berhasil dihapus.');
    }
}
