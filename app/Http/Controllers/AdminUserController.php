<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar semua user dengan pencarian berdasarkan nama.
     */
    public function index(Request $request)
    {
        $query = User::with('aplikasi')->orderBy('created_at', 'desc');

        // Filter pencarian berdasarkan nama
        if ($request->filled('nama')) {
            $query->where('name', 'like', '%' . $request->nama . '%');
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        $aplikasis = Aplikasi::orderBy('nama_aplikasi', 'asc')->get();
        return view('admin.users.create', compact('aplikasis'));
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nip'         => 'required|string|max:20|unique:users,nip',
            'email'       => 'required|email|max:255|unique:users,email',
            'name'        => 'required|string|max:255',
            'role'        => ['required', 'string', Rule::in(['admin helpdesk', 'admin aplikasi', 'teknisi', 'master_helpdesk'])],
            'aplikasi_id' => 'nullable|exists:aplikasis,id',
            'password'    => 'required|string|min:6|confirmed',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'aplikasi_id.exists' => 'Aplikasi yang dipilih tidak valid.',
        ]);

        if ($request->role === 'admin aplikasi' && !$request->aplikasi_id) {
            return back()->withErrors(['aplikasi_id' => 'Aplikasi wajib dipilih untuk role Admin Aplikasi.'])->withInput();
        }

        try {
            User::create([
                'nip'         => $request->nip,
                'email'       => $request->email,
                'name'        => $request->name,
                'role'        => $request->role,
                'aplikasi_id' => $request->role === 'admin aplikasi' ? $request->aplikasi_id : null,
                'password'    => Hash::make($request->password),
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Tampilkan detail user.
     */
    public function show(User $user)
    {
        $user->load('aplikasi');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit(User $user)
    {
        $aplikasis = Aplikasi::orderBy('nama_aplikasi', 'asc')->get();
        return view('admin.users.edit', compact('user', 'aplikasis'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nip'         => 'required|string|max:20|unique:users,nip,' . $user->id,
            'email'       => 'required|email|max:255|unique:users,email,' . $user->id,
            'name'        => 'required|string|max:255',
            'role'        => ['required', 'string', Rule::in(['admin helpdesk', 'admin aplikasi', 'teknisi', 'master_helpdesk'])],
            'aplikasi_id' => 'nullable|exists:aplikasis,id',
            'password'    => 'nullable|string|min:6|confirmed',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'aplikasi_id.exists' => 'Aplikasi yang dipilih tidak valid.',
        ]);

        if ($request->role === 'admin aplikasi' && !$request->aplikasi_id) {
            return back()->withErrors(['aplikasi_id' => 'Aplikasi wajib dipilih untuk role Admin Aplikasi.'])->withInput();
        }

        try {
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

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        try {
            if (auth()->id() === $user->id) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus akun yang sedang login.']);
            }

            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
