<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Spatie\RouteAttributes\RouteRegistrar;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ✅ Registrasi otomatis controller berbasis attribute (Spatie)
        (new RouteRegistrar(app()))
            ->useRootNamespace('App\Http\Controllers') // opsional tapi direkomendasikan
            ->register(app_path('Http/Controllers'));

        // ✅ Tambahkan ini kalau kamu masih pakai route/web.php (hybrid)
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
