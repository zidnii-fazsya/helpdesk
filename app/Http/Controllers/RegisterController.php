<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string|max:20',
            'role' => 'required|string',
        ]);

        // Cek apakah role menggunakan format role|kategori_aplikasi
        if (strpos($request->role, '|') !== false) {
            [$role, $kategori_aplikasi] = explode('|', $request->role, 2);
        } else {
            $role = $request->role;
            $kategori_aplikasi = null;
        }

        // Debug log
        Log::info('Mendaftarkan user admin helpdesk', [
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $role,
            'kategori_aplikasi' => $kategori_aplikasi,
        ]);

        try {
            $userData = [
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'role' => $role,
            ];

            // Tambahkan kategori_aplikasi jika kolomnya tersedia di tabel users
            if (Schema::hasColumn('users', 'kategori_aplikasi') && $kategori_aplikasi !== null) {
                $userData['kategori_aplikasi'] = $kategori_aplikasi;
            }

            $user = User::create($userData);

            Log::info('User berhasil dibuat: ID = ' . $user->id);

            return redirect()
                ->back()
                ->with('success', 'Akun admin helpdesk berhasil dibuat. Silakan login.');
        } catch (QueryException $e) {
            Log::error('QueryException saat registrasi user: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors(['error' => 'Gagal membuat akun. Periksa input atau struktur database Anda.'])
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Exception saat registrasi user: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withErrors(['error' => 'Gagal membuat akun. Silakan coba lagi.'])
                ->withInput();
        }
    }
}
