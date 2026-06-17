<?php

namespace App\Providers;

use App\Contracts\GameLogRepositoryInterface;
use App\Contracts\UserStatisticsRepositoryInterface;
use App\Repositories\GameLogRepository;
use App\Repositories\UserStatisticsRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Class GameLogServiceProvider
 *
 * Service Provider khusus untuk modul GameLog.
 * Bertanggung jawab mendaftarkan semua binding interface → implementasi
 * ke dalam Laravel Service Container (IoC Container).
 *
 * Dengan pola ini, Controller hanya bergantung pada Interface (abstraksi),
 * bukan pada kelas konkret (implementasi). Ini mempermudah:
 *  - Unit testing (bisa diganti dengan mock/fake)
 *  - Penggantian implementasi di masa depan (misal: ganti ke API eksternal)
 *    tanpa perlu mengubah satu baris pun di Controller.
 */
class GameLogServiceProvider extends ServiceProvider
{
    /**
     * Register bindings ke dalam Service Container.
     *
     * Menggunakan singleton() agar hanya satu instance yang dibuat
     * selama satu siklus request HTTP — lebih efisien daripada
     * membuat instance baru setiap kali interface di-resolve.
     */
    public function register(): void
    {
        // Binding: GameLogRepositoryInterface → GameLogRepository
        // Setiap kali Container diminta GameLogRepositoryInterface,
        // ia akan mengembalikan instance GameLogRepository.
        $this->app->singleton(
            GameLogRepositoryInterface::class,
            GameLogRepository::class
        );

        // Binding: UserStatisticsRepositoryInterface → UserStatisticsRepository
        $this->app->singleton(
            UserStatisticsRepositoryInterface::class,
            UserStatisticsRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * Dipanggil setelah semua provider lain selesai register.
     * Gunakan method ini untuk mendaftarkan event listener,
     * routes, atau hal lain yang bergantung pada service lain.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Daftar semua interface yang disediakan provider ini.
     * Berguna untuk deferred loading (lazy loading).
     *
     * @return array<class-string>
     */
    public function provides(): array
    {
        return [
            GameLogRepositoryInterface::class,
            UserStatisticsRepositoryInterface::class,
        ];
    }
}
