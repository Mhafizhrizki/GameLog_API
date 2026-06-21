<?php

namespace App\Contracts;

/**
 * Interface UserStatisticsServiceInterface
 *
 * Mendefinisikan kontrak untuk operasi statistik aktivitas game user.
 *
 * Service Layer ini menjadi perantara antara Controller dan Repository,
 * sehingga jika di masa depan ada transformasi atau logika tambahan
 * pada data statistik, cukup diubah di sini tanpa menyentuh Controller.
 */
interface UserStatisticsServiceInterface
{
    /**
     * Ambil ringkasan statistik game tracker milik user tertentu.
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
