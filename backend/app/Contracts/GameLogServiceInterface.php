<?php

namespace App\Contracts;

use App\Models\GameLog;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface GameLogServiceInterface
 *
 * Mendefinisikan kontrak (contract) untuk semua operasi bisnis
 * yang berkaitan dengan GameLog.
 *
 * Service Layer ini bertanggung jawab atas business logic seperti:
 * - Validasi duplikasi game
 * - Penentuan nilai default
 * - Verifikasi kepemilikan entri
 *
 * Controller tidak perlu tahu BAGAIMANA logika bisnis berjalan,
 * hanya perlu tahu APA yang bisa dilakukan.
 */
interface GameLogServiceInterface
{
    /**
     * Ambil semua game log milik user tertentu,
     * dengan filter status opsional.
     *
     * @param  int         $userId
     * @param  string|null $status  wishlist | playing | completed
     * @return Collection
     */
    public function getAll(int $userId, ?string $status = null): Collection;

    /**
     * Tambahkan game baru ke tracker user.
     * Melempar exception jika game sudah ada (duplikasi).
     *
     * @param  int   $userId
     * @param  array $data   Berisi rawg_id, title, status (opsional), personal_rating (opsional)
     * @return GameLog|null
     */
    public function add(int $userId, array $data): ?GameLog;

    /**
     * Perbarui entri GameLog yang sudah ada.
     * Memastikan entri tersebut milik user yang diberikan.
     * Mengembalikan null jika entri tidak ditemukan atau bukan milik user.
     *
     * @param  int   $userId
     * @param  int   $id
     * @param  array $data   Berisi status (opsional) dan/atau personal_rating (opsional)
     * @return GameLog|null
     */
    public function update(int $userId, int $id, array $data): ?GameLog;

    /**
     * Hapus entri GameLog dari tracker user.
     * Memastikan entri tersebut milik user yang diberikan.
     * Mengembalikan null jika entri tidak ditemukan atau bukan milik user.
     *
     * @param  int $userId
     * @param  int $id
     * @return bool|null  true jika berhasil dihapus, null jika tidak ditemukan
     */
    public function remove(int $userId, int $id): ?bool;
}
