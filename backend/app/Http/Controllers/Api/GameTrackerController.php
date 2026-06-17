<?php

namespace App\Http\Controllers\Api;

use App\Contracts\GameLogRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class GameTrackerController
 *
 * Menangani seluruh request HTTP untuk operasi CRUD GameLog.
 *
 * Controller ini tidak berinteraksi langsung dengan database.
 * Semua akses data didelegasikan ke GameLogRepositoryInterface,
 * yang disuntikkan (inject) oleh Laravel Service Container
 * secara otomatis melalui constructor.
 */
class GameTrackerController extends Controller
{
    public function __construct(
        private readonly GameLogRepositoryInterface $gameLogRepository
    ) {}

    /**
     * GET /api/v1/gamelogs
     * Tampilkan semua game tracker milik user yang sedang login.
     * Mendukung filter opsional: ?status=playing|wishlist|completed
     */
    public function index(Request $request): JsonResponse
    {
        $status   = $request->query('status');
        $gameLogs = $this->gameLogRepository->getAllForUser(
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

        // Cegah duplikasi: satu user tidak boleh menambah game yang sama dua kali
        if ($this->gameLogRepository->existsForUser($request->user()->id, $validated['rawg_id'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Game already exists in your tracker',
            ], 409);
        }

        $gameLog = $this->gameLogRepository->create([
            'user_id'         => $request->user()->id,
            'rawg_id'         => $validated['rawg_id'],
            'title'           => $validated['title'],
            'status'          => $validated['status'] ?? 'playing',
            'personal_rating' => $validated['personal_rating'] ?? 0,
        ]);

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
        // Repository sudah memastikan entri milik user ini
        $gameLog = $this->gameLogRepository->findByIdAndUser($id, $request->user()->id);

        if (! $gameLog) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found',
            ], 404);
        }

        $validated = $request->validate([
            'status'          => 'nullable|in:wishlist,playing,completed',
            'personal_rating' => 'nullable|integer|min:0|max:5',
        ]);

        // Hanya kirim field yang benar-benar diisi (bukan null)
        $dataToUpdate = array_filter($validated, fn ($value) => ! is_null($value));

        $updated = $this->gameLogRepository->update($gameLog, $dataToUpdate);

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
        // Repository sudah memastikan entri milik user ini
        $gameLog = $this->gameLogRepository->findByIdAndUser($id, $request->user()->id);

        if (! $gameLog) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found or unauthorized action',
            ], 404);
        }

        $this->gameLogRepository->delete($gameLog);

        return response()->json([
            'status'  => 'success',
            'message' => 'Game successfully removed from tracker',
        ], 200);
    }
}
