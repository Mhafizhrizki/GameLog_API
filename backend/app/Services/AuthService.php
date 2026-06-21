<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 *
 * Implementasi konkret dari AuthServiceInterface.
 * Lapisan ini menampung seluruh business logic autentikasi:
 * - Hashing password saat registrasi
 * - Verifikasi password saat login
 * - Pembuatan token Sanctum
 * - Penghapusan token saat logout
 *
 * Dengan memindahkan logika ini ke Service, AuthController
 * menjadi tipis dan hanya bertugas mengelola request/response HTTP.
 */
class AuthService implements AuthServiceInterface
{
    /**
     * Daftarkan user baru ke database dan buat token akses.
     *
     * Business logic:
     * - Password di-hash menggunakan bcrypt sebelum disimpan.
     * - Token dibuat segera setelah registrasi (auto-login).
     *
     * @param  array $data  Berisi name, email, password (sudah divalidasi)
     * @return array{user: User, token: string}
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Verifikasi credentials user dan buat token akses jika valid.
     *
     * Business logic:
     * - Cari user berdasarkan email.
     * - Bandingkan password menggunakan Hash::check().
     * - Jika credentials salah, kembalikan null.
     *
     * @param  array $credentials  Berisi email dan password
     * @return array{user: User, token: string}|null  null jika credentials tidak valid
     */
    public function login(array $credentials): ?array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Hapus token akses aktif milik user (logout dari device saat ini).
     *
     * @param  User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
