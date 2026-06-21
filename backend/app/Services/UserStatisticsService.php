<?php

namespace App\Services;

use App\Contracts\UserStatisticsRepositoryInterface;
use App\Contracts\UserStatisticsServiceInterface;

/**
 * Class UserStatisticsService
 *
 * Implementasi konkret dari UserStatisticsServiceInterface.
 * Bertindak sebagai perantara antara UserStatisticsController
 * dan UserStatisticsRepository.
 *
 * Dengan adanya layer ini, jika di masa depan diperlukan transformasi
 * atau penambahan logika pada data statistik (misalnya caching,
 * penggabungan dari sumber lain, dll), cukup diubah di sini
 * tanpa menyentuh Controller maupun Repository.
 */
class UserStatisticsService implements UserStatisticsServiceInterface
{
    public function __construct(
        private readonly UserStatisticsRepositoryInterface $statisticsRepository
    ) {}

    /**
     * Ambil ringkasan statistik game tracker milik user tertentu.
     * Mendelegasikan pengambilan data ke Repository.
     *
     * @param  int   $userId
     * @return array{
     *     total_games: int,
     *     total_completed: int,
     *     total_playing: int,
     *     total_wishlist: int,
     *     average_rating: float|int
     * }
     */
    public function getStatistics(int $userId): array
    {
        return $this->statisticsRepository->getStatistics($userId);
    }
}
