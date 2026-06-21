<?php

namespace App\Providers;

use App\Contracts\AuthServiceInterface;
use App\Contracts\GameLogRepositoryInterface;
use App\Contracts\GameLogServiceInterface;
use App\Contracts\UserStatisticsRepositoryInterface;
use App\Contracts\UserStatisticsServiceInterface;
use App\Repositories\GameLogRepository;
use App\Repositories\UserStatisticsRepository;
use App\Services\AuthService;
use App\Services\GameLogService;
use App\Services\UserStatisticsService;
use Illuminate\Support\ServiceProvider;

/**
 * Class GameLogServiceProvider
 *
 * Service Provider khusus untuk modul GameLog.
 * Bertanggung jawab mendaftarkan semua binding interface → implementasi
 * ke dalam Laravel Service Container (IoC Container).
 *
 * Arsitektur yang didaftarkan:
 *   Controller → Service Interface → Service → Repository Interface → Repository → Model
 *
 * Dengan pola ini:
 *  - Controller hanya bergantung pada Service Interface (abstraksi)
 *  - Service hanya bergantung pada Repository Interface (abstraksi)
 *  - Mempermudah unit testing (bisa diganti dengan mock/fake)
 *  - Penggantian implementasi di masa depan tidak memerlukan perubahan
 *    pada lapisan di atasnya.
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
        // -------------------------------------------------------
        // Repository Layer Bindings
        // -------------------------------------------------------

        // Binding: GameLogRepositoryInterface → GameLogRepository
        $this->app->singleton(
            GameLogRepositoryInterface::class,
            GameLogRepository::class
        );

        // Binding: UserStatisticsRepositoryInterface → UserStatisticsRepository
        $this->app->singleton(
            UserStatisticsRepositoryInterface::class,
            UserStatisticsRepository::class
        );

        // -------------------------------------------------------
        // Service Layer Bindings
        // -------------------------------------------------------

        // Binding: GameLogServiceInterface → GameLogService
        $this->app->singleton(
            GameLogServiceInterface::class,
            GameLogService::class
        );

        // Binding: AuthServiceInterface → AuthService
        $this->app->singleton(
            AuthServiceInterface::class,
            AuthService::class
        );

        // Binding: UserStatisticsServiceInterface → UserStatisticsService
        $this->app->singleton(
            UserStatisticsServiceInterface::class,
            UserStatisticsService::class
        );

        // Binding: RawgServiceInterface → RawgService
        $this->app->singleton(
            \App\Contracts\RawgServiceInterface::class,
            \App\Services\RawgService::class
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
            GameLogServiceInterface::class,
            AuthServiceInterface::class,
            UserStatisticsServiceInterface::class,
        ];
    }
}
