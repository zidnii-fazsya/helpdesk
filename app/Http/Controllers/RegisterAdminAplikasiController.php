<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Aplikasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterAdminAplikasiController extends Controller
{
    /**
     * Tampilkan form registrasi khusus admin aplikasi
     */
    public function showRegisterForm()
    {
        // Ambil semua nama_aplikasi dari tabel aplikasis
        $nama_aplikasi = Aplikasi::select('nama_aplikasi')->get();

        return view('auth.register_admin_aplikasi', compact('nama_aplikasi'));
    }

    /**
     * Proses penyimpanan data registrasi admin aplikasi ke database
     */
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'nip'            => 'required|string|max:20|unique:users,nip',
            'email'          => 'required|email|unique:users,email',
            'no_hp'          => 'required|string|max:15',
            'nama_aplikasi'  => 'required|string|exists:aplikasis,nama_aplikasi',
            'password'       => 'required|confirmed|min:6',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor handphone wajib diisi.',
            'nama_aplikasi.required' => 'Aplikasi wajib dipilih.',
            'nama_aplikasi.exists' => 'Aplikasi yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Format role: "admin aplikasi {nama_aplikasi}" (huruf kecil semua)
        $role = 'admin aplikasi ' . strtolower(trim($request->nama_aplikasi));

        try {
            // Simpan user ke tabel users
            User::create([
                'name'     => trim($request->name),
                'nip'      => trim($request->nip),
                'email'    => trim($request->email),
                'no_hp'    => trim($request->no_hp),
                'role'     => $role,
                'password' => Hash::make($request->password),
            ]);

            // Redirect ke halaman login admin aplikasi dengan pesan sukses
            return redirect()->route('login.admin.aplikasi.form')
                             ->with('success', 'Registrasi berhasil! Silakan login sebagai Admin Aplikasi.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'])
                ->withInput();
        }
    }
}
