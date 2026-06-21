<?php

namespace App\Http\Controllers\Api;

use App\Contracts\GameLogServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class GameTrackerController
 *
 * Menangani seluruh request HTTP untuk operasi CRUD GameLog.
 *
 * Controller ini tidak mengandung business logic.
 * Semua logika bisnis (validasi duplikasi, kepemilikan entri,
 * penentuan nilai default) didelegasikan ke GameLogServiceInterface,
 * yang disuntikkan (inject) oleh Laravel Service Container
 * secara otomatis melalui constructor.
 *
 * Alur: Controller → Service → Repository → Model
 */
class GameTrackerController extends Controller
{
    public function __construct(
        private readonly GameLogServiceInterface $gameLogService
    ) {}

    /**
     * GET /api/v1/gamelogs
     * Tampilkan semua game tracker milik user yang sedang login.
     * Mendukung filter opsional: ?status=playing|wishlist|completed
     */
    public function index(Request $request): JsonResponse
    {
        $status   = $request->query('status');
        $gameLogs = $this->gameLogService->getAll(
            userId: $request->user()->id,
            status: $status
        );

        return response()->json([
            'status' => 'success',
            'data'   => $gameLogs,
        ], 200);
    }

    /**
     * POST /api/v1/gamelogs
     * Tambahkan game baru ke tracker pengguna.
     * Menolak duplikasi berdasarkan rawg_id + user_id.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rawg_id'         => 'required|integer',
            'title'           => 'required|string|max:255',
            'status'          => 'nullable|in:wishlist,playing,completed',
            'personal_rating' => 'nullable|integer|min:0|max:5',
        ]);

        // Service mengembalikan null jika game sudah ada (duplikasi)
        $gameLog = $this->gameLogService->add($request->user()->id, $validated);

        if (! $gameLog) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Game already exists in your tracker',
            ], 409);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Game successfully added to tracker',
            'data'    => [
                'id'              => $gameLog->id,
                'rawg_id'         => $gameLog->rawg_id,
                'title'           => $gameLog->title,
                'status'          => $gameLog->status,
                'personal_rating' => $gameLog->personal_rating,
                'created_at'      => $gameLog->created_at,
            ],
        ], 201);
    }

    /**
     * PUT /api/v1/gamelogs/{id}
     * Perbarui status dan/atau rating game yang sudah ada di tracker.
     * Hanya pemilik yang boleh mengubah datanya sendiri.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status'          => 'nullable|in:wishlist,playing,completed',
            'personal_rating' => 'nullable|integer|min:0|max:5',
        ]);

        // Service mengembalikan null jika entri tidak ditemukan atau bukan milik user
        $updated = $this->gameLogService->update($request->user()->id, $id, $validated);

        if (! $updated) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'GameLog updated successfully',
            'data'    => [
                'id'              => $updated->id,
                'title'           => $updated->title,
                'status'          => $updated->status,
                'personal_rating' => $updated->personal_rating,
                'updated_at'      => $updated->updated_at,
            ],
        ], 200);
    }

    /**
     * DELETE /api/v1/gamelogs/{id}
     * Hapus permanen entri game dari tracker pengguna.
     * Hanya pemilik yang boleh menghapus datanya sendiri.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        // Service mengembalikan null jika entri tidak ditemukan atau bukan milik user
        $result = $this->gameLogService->remove($request->user()->id, $id);

        if (is_null($result)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found or unauthorized action',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Game successfully removed from tracker',
        ], 200);
    }
}
