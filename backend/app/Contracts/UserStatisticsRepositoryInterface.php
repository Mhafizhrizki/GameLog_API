<?php

namespace App\Contracts;

/**
 * Interface UserStatisticsRepositoryInterface
 *
 * Mendefinisikan kontrak untuk mengambil data statistik
 * aktivitas game dari seorang pengguna.
 */
interface UserStatisticsRepositoryInterface
{
    /**
     * Hitung dan kembalikan ringkasan statistik game tracker
     * milik user tertentu.
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
    public function getStatistics(int $userId): array;
}
