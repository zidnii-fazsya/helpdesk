<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua user.
     */
    public function index()
    {
        // Ambil semua user beserta nama aplikasi jika dia admin aplikasi
        $users = User::with('aplikasi')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        $aplikasis = Aplikasi::all();
        return view('users.create', compact('aplikasis'));
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip'         => 'required|unique:users,nip',
            'email'       => 'required|email|unique:users,email',
            'name'        => 'required|string|max:255',
            'role'        => 'required|string',
            'aplikasi_id' => 'nullable|exists:aplikasis,id',
            'password'    => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'nip'         => $request->nip,
            'email'       => $request->email,
            'name'        => $request->name,
            'role'        => $request->role,
            'aplikasi_id' => $request->role === 'admin aplikasi' ? $request->aplikasi_id : null,
            'password'    => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit(User $user)
    {
        $aplikasis = Aplikasi::all();
        return view('users.edit', compact('user', 'aplikasis'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nip'         => 'required|unique:users,nip,' . $user->id,
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'name'        => 'required|string|max:255',
            'role'        => 'required|string',
            'aplikasi_id' => 'nullable|exists:aplikasis,id',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'nip'         => $request->nip,
            'email'       => $request->email,
            'name'        => $request->name,
            'role'        => $request->role,
            'aplikasi_id' => $request->role === 'admin aplikasi' ? $request->aplikasi_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
