<?php

namespace App\Repositories;

use App\Contracts\UserStatisticsRepositoryInterface;
use App\Models\GameLog;

/**
 * Class UserStatisticsRepository
 *
 * Implementasi konkret dari UserStatisticsRepositoryInterface.
 * Bertanggung jawab mengambil dan menghitung statistik
 * aktivitas game milik seorang pengguna dari database.
 */
class UserStatisticsRepository implements UserStatisticsRepositoryInterface
{
    /**
     * Hitung statistik game tracker lengkap milik seorang user:
     * - total game yang ditambahkan
     * - jumlah per status (completed, playing, wishlist)
     * - rata-rata rating personal (dikecualikan rating 0)
     */
    public function getStatistics(int $userId): array
    {
        $totalGames     = GameLog::where('user_id', $userId)->count();
        $totalCompleted = GameLog::where('user_id', $userId)->where('status', 'completed')->count();
        $totalPlaying   = GameLog::where('user_id', $userId)->where('status', 'playing')->count();
        $totalWishlist  = GameLog::where('user_id', $userId)->where('status', 'wishlist')->count();

        $averageRating = GameLog::where('user_id', $userId)
            ->where('personal_rating', '>', 0)
            ->avg('personal_rating');

        return [
            'total_games'     => $totalGames,
            'total_completed' => $totalCompleted,
            'total_playing'   => $totalPlaying,
            'total_wishlist'  => $totalWishlist,
            'average_rating'  => $averageRating ? round((float) $averageRating, 1) : 0,
        ];
    }
}
