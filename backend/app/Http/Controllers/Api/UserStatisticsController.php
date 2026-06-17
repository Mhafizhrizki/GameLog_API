<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserStatisticsRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UserStatisticsController
 *
 * Menangani request untuk mengambil statistik aktivitas
 * game dari pengguna yang sedang login.
 *
 * Semua logika query database didelegasikan ke
 * UserStatisticsRepositoryInterface yang disuntikkan
 * oleh Laravel Service Container melalui constructor.
 */
class UserStatisticsController extends Controller
{
    public function __construct(
        private readonly UserStatisticsRepositoryInterface $statisticsRepository
    ) {}

    /**
     * GET /api/v1/user/statistics
     * Kembalikan ringkasan statistik game tracker milik user yang login.
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $statistics = $this->statisticsRepository->getStatistics(
            userId: $request->user()->id
        );

        return response()->json([
            'status' => 'success',
            'data'   => $statistics,
        ], 200);
    }
}
