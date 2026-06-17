<?php

namespace App\Repositories;

use App\Contracts\GameLogRepositoryInterface;
use App\Models\GameLog;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class GameLogRepository
 *
 * Implementasi konkret dari GameLogRepositoryInterface.
 * Semua interaksi dengan database (Eloquent) untuk entitas
 * GameLog ditulis di sini, sehingga controller tetap bersih
 * dan hanya mengandung logika HTTP.
 */
class GameLogRepository implements GameLogRepositoryInterface
{
    /**
     * Ambil semua game log milik seorang user,
     * dengan filter status opsional.
     */
    public function getAllForUser(int $userId, ?string $status = null): Collection
    {
        $query = GameLog::where('user_id', $userId);

        if ($status && in_array($status, ['wishlist', 'playing', 'completed'])) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Cari satu entri GameLog berdasarkan ID sekaligus
     * memastikan entri tersebut memang milik user yang dimaksud.
     * Mengembalikan null jika tidak ditemukan atau bukan milik user.
     */
    public function findByIdAndUser(int $id, int $userId): ?GameLog
    {
        return GameLog::where('id', $id)
                      ->where('user_id', $userId)
                      ->first();
    }

    /**
     * Periksa apakah game dengan rawg_id tertentu sudah
     * ada di tracker milik user (untuk validasi duplikasi).
     */
    public function existsForUser(int $userId, int $rawgId): bool
    {
        return GameLog::where('user_id', $userId)
                      ->where('rawg_id', $rawgId)
                      ->exists();
    }

    /**
     * Simpan entri GameLog baru ke database.
     */
    public function create(array $data): GameLog
    {
        return GameLog::create($data);
    }

    /**
     * Perbarui field pada entri GameLog yang sudah ada.
     * Hanya field yang ada di $data yang akan diupdate.
     */
    public function update(GameLog $gameLog, array $data): GameLog
    {
        $gameLog->update($data);

        // Refresh dari database agar mendapatkan nilai terbaru
        return $gameLog->fresh();
    }

    /**
     * Hapus entri GameLog dari database secara permanen.
     */
    public function delete(GameLog $gameLog): bool
    {
        return (bool) $gameLog->delete();
    }
}
