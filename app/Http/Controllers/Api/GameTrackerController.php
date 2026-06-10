<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameLog;
use Illuminate\Http\Request;

class GameTrackerController extends Controller
{
    /**
     * Display all game logs for the authenticated user.
     *
     * GET /api/v1/gamelogs
     * Optional query param: ?status=playing|wishlist|completed
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = GameLog::where('user_id', $request->user()->id);

        // Filter opsional berdasarkan status
        if ($request->has('status') && in_array($request->status, ['wishlist', 'playing', 'completed'])) {
            $query->where('status', $request->status);
        }

        $gameLogs = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data'   => $gameLogs,
        ], 200);
    }

    /**
     * Store a new game log entry for the authenticated user.
     *
     * POST /api/v1/gamelogs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rawg_id'         => 'required|integer',
            'title'           => 'required|string|max:255',
            'status'          => 'nullable|in:wishlist,playing,completed',
            'personal_rating' => 'nullable|integer|min:0|max:5',
        ]);

        // Cek duplikasi: user yang sama tidak boleh menambahkan game yang sama dua kali
        $exists = GameLog::where('user_id', $request->user()->id)
                         ->where('rawg_id', $validated['rawg_id'])
                         ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Game already exists in your tracker',
            ], 409);
        }

        $gameLog = GameLog::create([
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
     * Update the status and/or personal rating of a game log entry.
     *
     * PUT /api/v1/gamelogs/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $gameLog = GameLog::find($id);

        // Cek apakah entri ada
        if (! $gameLog) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found',
            ], 404);
        }

        // Cek kepemilikan: pastikan data ini milik user yang sedang login
        if ($gameLog->user_id !== $request->user()->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized action. This log does not belong to you.',
            ], 403);
        }

        $validated = $request->validate([
            'status'          => 'nullable|in:wishlist,playing,completed',
            'personal_rating' => 'nullable|integer|min:0|max:5',
        ]);

        $gameLog->update(array_filter($validated, fn ($value) => ! is_null($value)));

        return response()->json([
            'status'  => 'success',
            'message' => 'GameLog updated successfully',
            'data'    => [
                'id'              => $gameLog->id,
                'title'           => $gameLog->title,
                'status'          => $gameLog->status,
                'personal_rating' => $gameLog->personal_rating,
                'updated_at'      => $gameLog->updated_at,
            ],
        ], 200);
    }

    /**
     * Remove a game log entry from the tracker.
     *
     * DELETE /api/v1/gamelogs/{id}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $gameLog = GameLog::find($id);

        // Cek apakah entri ada
        if (! $gameLog) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found or unauthorized action',
            ], 404);
        }

        // Cek kepemilikan: pastikan data ini milik user yang sedang login
        if ($gameLog->user_id !== $request->user()->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'GameLog entry not found or unauthorized action',
            ], 404);
        }

        $gameLog->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Game successfully removed from tracker',
        ], 200);
    }
}
