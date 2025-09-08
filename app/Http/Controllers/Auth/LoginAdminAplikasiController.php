<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Aplikasi;

class LoginAdminAplikasiController extends Controller
{
    /**
     * Tampilkan form login untuk admin aplikasi.
     */
    public function showLoginForm()
    {
        // Ambil semua nama aplikasi dari tabel aplikasis
        $nama_aplikasi = Aplikasi::select('nama_aplikasi')->get();

        return view('auth.login_admin_aplikasi', compact('nama_aplikasi'));
    }

    /**
     * Proses login admin aplikasi berdasarkan NIP, role, dan password.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string',
            'role' => 'required|string',
            'password' => 'required|string',
            '_token' => 'required' // pastikan CSRF token ada
        ]);

        // Cari user berdasarkan NIP dan role
        $user = User::where('nip', $request->nip)
                    ->whereRaw('LOWER(role) = ?', [strtolower($request->role)])
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {

            // Cek apakah role diawali dengan "admin aplikasi"
            if (Str::startsWith(strtolower(trim($user->role)), 'admin aplikasi')) {
                Auth::login($user);
                $request->session()->regenerate(); // cegah session fixation

                return redirect()
                    ->route('aplikasi.dashboard')
                    ->with('success', 'Login berhasil! Selamat datang di dashboard admin aplikasi.');
            }

            // Jika bukan admin aplikasi
            return redirect()
                ->route('login.admin.aplikasi.form')
                ->withErrors(['role' => 'Akses ditolak. Anda bukan admin aplikasi.'])
                ->withInput($request->only('nip'));
        }

        // Jika NIP, role, atau password salah
        return redirect()
            ->route('login.admin.aplikasi.form')
            ->withErrors(['nip' => 'NIP, role, atau password tidak sesuai.'])
            ->withInput($request->only('nip'));
    }

    /**
     * Logout admin aplikasi.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.admin.aplikasi.form')
            ->with('success', 'Anda berhasil logout dari admin aplikasi.');
    }
}
