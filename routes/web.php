<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    RegisterController,
    LoginController,
    DashboardController,
    TicketController,
    ReportsController,
    AdminAplikasiController,
    AdminUserController,
    AplikasiController,
    KategoriController,
    RegisterAdminAplikasiController,
    ProgressTicketController
};
use App\Http\Controllers\Auth\LoginAdminAplikasiController;
use App\Http\Middleware\AdminAplikasiMiddleware;
use App\Http\Controllers\Admin\SubKategoriController;

/*
|--------------------------------------------------------------------------
| AUTHENTIKASI UMUM (Admin Helpdesk)
|--------------------------------------------------------------------------
*/

// Form Register Admin Helpdesk
Route::get('/register', [RegisterController::class, 'show'])->name('register.admin.helpdesk.form');
Route::post('/register', [RegisterController::class, 'store'])->name('register.admin.helpdesk');

// Login Admin Helpdesk
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');

// Logout Admin Helpdesk
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTIKASI ADMIN APLIKASI (Tanpa Middleware Auth)
|--------------------------------------------------------------------------
*/

// Form Register Admin Aplikasi
Route::get('/register-admin-aplikasi', [RegisterAdminAplikasiController::class, 'showRegisterForm'])->name('register.admin.aplikasi.form');
Route::post('/register-admin-aplikasi', [RegisterAdminAplikasiController::class, 'register'])->name('register.admin.aplikasi');

// Login Admin Aplikasi
Route::get('/login-admin-aplikasi', [LoginAdminAplikasiController::class, 'showLoginForm'])->name('login.admin.aplikasi.form');
Route::post('/login-admin-aplikasi', [LoginAdminAplikasiController::class, 'login'])->name('login.admin.aplikasi.submit');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN HELPDESK (Middleware auth + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Ambil detail tiket untuk modal (AJAX)
        Route::get('/tickets/{id}/detail', [TicketController::class, 'detail'])->name('tickets.detail');
        
        // Tiket CRUD dan Filter berdasarkan status
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [TicketController::class, 'masuk'])->name('index');
            Route::get('/masuk', [TicketController::class, 'masuk'])->name('masuk');
            Route::get('/proses', [TicketController::class, 'proses'])->name('proses');
            Route::get('/selesai', [TicketController::class, 'selesai'])->name('selesai');
            Route::get('/all', [TicketController::class, 'all'])->name('all');
            Route::get('/create', [TicketController::class, 'create'])->name('create');
            Route::post('/', [TicketController::class, 'store'])->name('store');
            Route::put('/{id}/status', [TicketController::class, 'updateStatus'])->name('updateStatus');

            // Edit & Update tiket
            Route::get('/{id}/edit', [TicketController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TicketController::class, 'update'])->name('update');

            // Tandai tiket selesai
            Route::post('/{id}/selesai', [TicketController::class, 'markAsSelesai'])->name('markAsSelesai');

            // Hapus tiket
            Route::delete('/{id}', [TicketController::class, 'destroy'])->name('destroy');

            // Progress popup tiket
            Route::get('/{ticket}/progress', [TicketController::class, 'getProgress'])->name('progress.view');
        });

        // ✅ Route AJAX untuk ambil SubKategori & Aplikasi berdasarkan Kategori
        Route::get('/get-options', [TicketController::class, 'getOptions'])->name('get-options');

        // Laporan tiket
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('reports');
            Route::get('/export-bulanan', [ReportsController::class, 'exportBulanan'])->name('reports.export.bulanan');
            Route::get('/export-tahunan', [ReportsController::class, 'exportTahunan'])->name('reports.export.tahunan');
        });

        // Master Data Management
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('aplikasi', AplikasiController::class)->except(['show']);
        Route::resource('kategori', KategoriController::class)->except(['show']);

        /*
        |--------------------------------------------------------------------------
        | ROUTE SUBKATEGORI (nested dalam kategori)
        |--------------------------------------------------------------------------
        */
        Route::prefix('kategori/{kategori}/subkategori')->name('subkategori.')->group(function () {
            Route::get('/', [SubKategoriController::class, 'index'])->name('index');
            Route::get('/create', [SubKategoriController::class, 'create'])->name('create');
            Route::post('/', [SubKategoriController::class, 'store'])->name('store');

            Route::get('/{subkategori}/edit', [SubKategoriController::class, 'edit'])->name('edit');
            Route::put('/{subkategori}', [SubKategoriController::class, 'update'])->name('update');
            Route::delete('/{subkategori}', [SubKategoriController::class, 'destroy'])->name('destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN APLIKASI (Middleware auth + admin aplikasi khusus)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminAplikasiMiddleware::class])
    ->prefix('aplikasi')
    ->name('aplikasi.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminAplikasiController::class, 'index'])->name('dashboard');

        // Tiket
        Route::get('/tickets', [AdminAplikasiController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/masuk', [AdminAplikasiController::class, 'tiketMasuk'])->name('tickets.masuk');
        Route::get('/tickets/proses', [AdminAplikasiController::class, 'tiketProses'])->name('tickets.proses');
        Route::get('/tickets/selesai', [AdminAplikasiController::class, 'tiketSelesai'])->name('tickets.selesai');

        // Ambil Tiket
        Route::post('/tickets/ambil/{id}', [AdminAplikasiController::class, 'ambilTiket'])->name('tickets.ambil');

        // Assign Teknisi
        Route::post('/tickets/{id}/assign', [AdminAplikasiController::class, 'assignTeknisi'])->name('tickets.assign');

        // Progres Tiket tanpa AJAX
        Route::get('/tickets/{ticket}/progress', [ProgressTicketController::class, 'showForm'])->name('tickets.progress.form');
        Route::post('/tickets/{ticket}/progress', [ProgressTicketController::class, 'store'])->name('tickets.progress.store');

        // Tandai tiket selesai
        Route::post('/tickets/{ticket}/selesai', [ProgressTicketController::class, 'markAsSelesai'])->name('tickets.markAsSelesai');

        // Laporan
        Route::get('/laporan', [AdminAplikasiController::class, 'laporan'])->name('reports');
        Route::get('/laporan/bulanan', [AdminAplikasiController::class, 'exportBulanan'])->name('reports.bulanan');
        Route::get('/laporan/tahunan', [AdminAplikasiController::class, 'exportTahunan'])->name('reports.tahunan');

        // Logout
        Route::post('/logout', [LoginAdminAplikasiController::class, 'logout'])->name('logout');
    });

/*
|--------------------------------------------------------------------------
| FALLBACK LOGOUT UNTUK ADMIN APLIKASI
|--------------------------------------------------------------------------
*/
Route::post('/logout-admin-aplikasi', [LoginAdminAplikasiController::class, 'logout'])->name('logout.admin.aplikasi');
