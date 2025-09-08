<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAplikasiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login.admin.aplikasi.form')->withErrors([
                'login' => 'Silakan login terlebih dahulu untuk mengakses halaman admin aplikasi.'
            ]);
        }

        $user = Auth::user();

        // Role harus diawali dengan "admin aplikasi"
        if (str_starts_with(strtolower(trim($user->role)), 'admin aplikasi')) {
            return $next($request);
        }

        // Jika bukan admin aplikasi, logout dan redirect
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.admin.aplikasi.form')->withErrors([
            'login' => 'Anda tidak memiliki izin sebagai admin aplikasi.'
        ]);
    }
}
