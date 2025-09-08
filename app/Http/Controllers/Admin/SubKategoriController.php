<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\SubKategori; // ✅ gunakan model dengan nama yang benar
use Illuminate\Http\Request;

class SubKategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Tampilkan daftar subkategori untuk kategori tertentu.
     */
    public function index(Kategori $kategori)
    {
        $subkategoris = $kategori->subKategoris()->latest()->get();

        return view('admin.subkategori.index', compact('subkategoris', 'kategori'));
    }

    /**
     * Tampilkan form tambah subkategori untuk kategori tertentu.
     */
    public function create(Kategori $kategori)
    {
        $kategoriList = Kategori::all(); // ✅ untuk dropdown kategori
        return view('admin.subkategori.create', compact('kategori', 'kategoriList'));
    }

    /**
     * Simpan subkategori baru ke database.
     */
    public function store(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_subkategori' => 'required|string|max:255',
        ]);

        SubKategori::create([
            'nama_subkategori' => $validated['nama_subkategori'],
            'kategori_id'      => $kategori->id,
        ]);

        return redirect()
            ->route('admin.subkategori.index', $kategori->id) // ✅ langsung $kategori->id
            ->with('success', 'Subkategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit subkategori.
     */
    public function edit(Kategori $kategori, SubKategori $subkategori)
    {
        $kategoriList = Kategori::all(); // ✅ untuk dropdown kategori
        return view('admin.subkategori.edit', compact('subkategori', 'kategori', 'kategoriList'));
    }

    /**
     * Update data subkategori di database.
     */
    public function update(Request $request, Kategori $kategori, SubKategori $subkategori)
    {
        $validated = $request->validate([
            'nama_subkategori' => 'required|string|max:255',
        ]);

        $subkategori->update([
            'nama_subkategori' => $validated['nama_subkategori'],
            'kategori_id'      => $kategori->id,
        ]);

        return redirect()
            ->route('admin.subkategori.index', $kategori->id) // ✅ tambahkan parameter kategori
            ->with('success', 'Subkategori berhasil diperbarui.');
    }

    /**
     * Hapus subkategori dari database.
     */
    public function destroy(Kategori $kategori, SubKategori $subkategori)
    {
        $subkategori->delete();

        return redirect()
            ->route('admin.subkategori.index', $kategori->id) // ✅ tambahkan parameter kategori
            ->with('success', 'Subkategori berhasil dihapus.');
    }
}
