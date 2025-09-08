<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini hanya mengizinkan user dengan role 'admin'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login.form')->withErrors([
                'email' => 'Silakan login terlebih dahulu untuk mengakses halaman ini.'
            ]);
        }

        // ❌ Tolak jika bukan admin
        if (Auth::user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Admin access required.',
                    'error' => 'Anda tidak memiliki akses sebagai admin.'
                ], 403);
            }

            return redirect()->route('login.form')->withErrors([
                'email' => 'Anda tidak memiliki akses sebagai admin.'
            ]);
        }

        // ✅ Izinkan lanjut ke request berikutnya
        return $next($request);
    }
}
