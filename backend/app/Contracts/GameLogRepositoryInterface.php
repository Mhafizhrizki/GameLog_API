<?php

namespace App\Contracts;

use App\Models\GameLog;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface GameLogRepositoryInterface
 *
 * Mendefinisikan kontrak (contract) untuk semua operasi
 * yang berkaitan dengan data GameLog di database.
 * Controller tidak perlu tahu bagaimana data diambil,
 * hanya perlu tahu apa yang bisa dilakukan.
 */
interface GameLogRepositoryInterface
{
    /**
     * Ambil semua game log milik user tertentu,
     * dengan filter status opsional.
     *
     * @param  int         $userId
     * @param  string|null $status  wishlist | playing | completed
     * @return Collection
     */
    public function getAllForUser(int $userId, ?string $status = null): Collection;

    /**
     * Temukan satu entri GameLog berdasarkan ID,
     * dan pastikan entri tersebut milik user yang diberikan.
     *
     * @param  int      $id
     * @param  int      $userId
     * @return GameLog|null
     */
    public function findByIdAndUser(int $id, int $userId): ?GameLog;

    /**
     * Periksa apakah sebuah game (berdasarkan rawg_id) sudah
     * ada di tracker milik user tertentu (untuk cegah duplikasi).
     *
     * @param  int  $userId
     * @param  int  $rawgId
     * @return bool
     */
    public function existsForUser(int $userId, int $rawgId): bool;

    /**
     * Buat entri GameLog baru di database.
     *
     * @param  array   $data
     * @return GameLog
     */
    public function create(array $data): GameLog;

    /**
     * Perbarui entri GameLog yang sudah ada.
     *
     * @param  GameLog $gameLog
     * @param  array   $data
     * @return GameLog
     */
    public function update(GameLog $gameLog, array $data): GameLog;

    /**
     * Hapus entri GameLog dari database.
     *
     * @param  GameLog $gameLog
     * @return bool
     */
    public function delete(GameLog $gameLog): bool;
}
