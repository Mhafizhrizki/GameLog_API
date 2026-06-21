<?php

namespace App\Http\Controllers\Api;

use App\Contracts\RawgServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class RawgController
 *
 * Menangani request pencarian game ke RAWG API yang diproxy melalui backend.
 */
class RawgController extends Controller
{
    public function __construct(
        private readonly RawgServiceInterface $rawgService
    ) {}

    /**
     * GET /api/v1/games/search
     * Cari game dari RAWG API.
     */
    public function searchGames(Request $request): JsonResponse
    {
        $query = $request->query('search', '');
        $page = (int) $request->query('page', 1);

        $result = $this->rawgService->searchGames($query, $page);

        if ($result['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
            ], 500);
        }

        return response()->json($result, 200);
    }
}
