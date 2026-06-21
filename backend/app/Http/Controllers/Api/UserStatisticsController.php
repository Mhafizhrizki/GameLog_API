<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserStatisticsServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UserStatisticsController
 *
 * Menangani request untuk mengambil statistik aktivitas
 * game dari pengguna yang sedang login.
 *
 * Semua logika pengambilan dan perhitungan statistik didelegasikan ke
 * UserStatisticsServiceInterface yang disuntikkan oleh Laravel Service Container
 * melalui constructor.
 *
 * Alur: Controller → Service → Repository → Model
 */
class UserStatisticsController extends Controller
{
    public function __construct(
        private readonly UserStatisticsServiceInterface $statisticsService
    ) {}

    /**
     * GET /api/v1/user/statistics
     * Kembalikan ringkasan statistik game tracker milik user yang login.
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $statistics = $this->statisticsService->getStatistics(
            userId: $request->user()->id
        );

        return response()->json([
            'status' => 'success',
            'data'   => $statistics,
        ], 200);
    }
}
