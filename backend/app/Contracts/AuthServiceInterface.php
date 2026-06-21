<?php

namespace App\Contracts;

use App\Models\User;

/**
 * Interface AuthServiceInterface
 *
 * Kontrak untuk operasi autentikasi (Register, Login, Logout).
 */
interface AuthServiceInterface
{
    /**
     * Daftarkan user baru ke database dan buat token akses.
     *
     * @param  array $data
     * @return array
     */
    public function register(array $data): array;

    /**
     * Verifikasi credentials user dan buat token akses jika valid.
     *
     * @param  array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array;

    /**
     * Hapus token akses aktif milik user (logout dari device saat ini).
     *
     * @param  User $user
     * @return void
     */
    public function logout(User $user): void;
}
