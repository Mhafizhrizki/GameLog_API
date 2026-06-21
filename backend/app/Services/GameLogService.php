<?php

namespace App\Services;

use App\Contracts\GameLogRepositoryInterface;
use App\Contracts\GameLogServiceInterface;
use App\Models\GameLog;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class GameLogService
 *
 * Implementasi konkret dari GameLogServiceInterface.
 * Lapisan ini menampung seluruh business logic untuk operasi GameLog:
 * - Mencegah duplikasi game (rawg_id + user_id)
 * - Menentukan nilai default untuk field opsional
 * - Memverifikasi kepemilikan entri sebelum update/delete
 *
 * Service ini hanya bergantung pada GameLogRepositoryInterface (abstraksi),
 * bukan implementasi konkret, sehingga mudah di-mock saat unit testing.
 */
class GameLogService implements GameLogServiceInterface
{
    public function __construct(
        private readonly GameLogRepositoryInterface $gameLogRepository
    ) {}

    /**
     * Ambil semua game log milik user tertentu,
     * dengan filter status opsional.
     */
    public function getAll(int $userId, ?string $status = null): Collection
    {
        return $this->gameLogRepository->getAllForUser($userId, $status);
    }

    /**
     * Tambahkan game baru ke tracker user.
     *
     * Business rules:
     * - Satu user tidak boleh menambahkan game yang sama (berdasarkan rawg_id) dua kali.
     * - Jika status tidak diisi, default ke 'playing'.
     * - Jika personal_rating tidak diisi, default ke 0.
     *
     * @return GameLog|null  null jika game sudah ada di tracker (duplikasi)
     */
    public function add(int $userId, array $data): ?GameLog
    {
        // Cegah duplikasi: satu user tidak boleh menambah game yang sama dua kali
        if ($this->gameLogRepository->existsForUser($userId, $data['rawg_id'])) {
            return null;
        }

        return $this->gameLogRepository->create([
            'user_id'         => $userId,
            'rawg_id'         => $data['rawg_id'],
            'title'           => $data['title'],
            'status'          => $data['status'] ?? 'playing',
            'personal_rating' => $data['personal_rating'] ?? 0,
        ]);
    }

    /**
     * Perbarui entri GameLog yang sudah ada.
     *
     * Business rules:
     * - Entri harus milik user yang melakukan request.
     * - Hanya field yang dikirim (tidak null) yang akan diperbarui.
     *
     * @return GameLog|null  null jika entri tidak ditemukan atau bukan milik user
     */
    public function update(int $userId, int $id, array $data): ?GameLog
    {
        $gameLog = $this->gameLogRepository->findByIdAndUser($id, $userId);

        if (! $gameLog) {
            return null;
        }

        // Hanya kirim field yang benar-benar diisi (bukan null)
        $dataToUpdate = array_filter($data, fn ($value) => ! is_null($value));

        return $this->gameLogRepository->update($gameLog, $dataToUpdate);
    }

    /**
     * Hapus entri GameLog dari tracker user.
     *
     * Business rules:
     * - Entri harus milik user yang melakukan request.
     *
     * @return bool|null  true jika berhasil dihapus, null jika entri tidak ditemukan
     */
    public function remove(int $userId, int $id): ?bool
    {
        $gameLog = $this->gameLogRepository->findByIdAndUser($id, $userId);

        if (! $gameLog) {
            return null;
        }

        return $this->gameLogRepository->delete($gameLog);
    }
}
