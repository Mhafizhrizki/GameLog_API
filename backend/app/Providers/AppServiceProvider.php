<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * Service Provider utama (global) aplikasi Laravel.
 * Dijalankan pertama kali saat aplikasi boot.
 *
 * Tanggung jawab provider ini adalah konfigurasi GLOBAL:
 * - Pengaturan Eloquent / Model
 * - Konfigurasi default framework
 *
 * Binding interface → implementasi yang spesifik per modul
 * didelegasikan ke GameLogServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Aktifkan strict mode Eloquent di environment non-production.
        // Mencegah lazy loading (N+1 problem), akses kolom yang tidak ada,
        // dan assignment ke kolom yang tidak ada di $fillable.
        Model::shouldBeStrict(! $this->app->isProduction());
    }
}
