<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AuthServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class AuthController
 *
 * Menangani seluruh request HTTP untuk autentikasi pengguna.
 *
 * Controller ini tidak mengandung business logic autentikasi.
 * Semua logika (hashing password, verifikasi credentials, pembuatan token)
 * didelegasikan ke AuthServiceInterface, yang disuntikkan oleh
 * Laravel Service Container secara otomatis melalui constructor.
 *
 * Alur: Controller → Service → Model
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * POST /api/v1/register
     * Daftarkan user baru dan kembalikan token akses.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->authService->register($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'User registered successfully',
            'data'    => [
                'user'  => [
                    'id'         => $result['user']->id,
                    'name'       => $result['user']->name,
                    'email'      => $result['user']->email,
                    'created_at' => $result['user']->created_at,
                ],
                'token' => $result['token'],
            ],
        ], 201);
    }

    /**
     * POST /api/v1/login
     * Verifikasi credentials dan kembalikan token akses.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Service mengembalikan null jika credentials tidak valid
        $result = $this->authService->login($validated);

        if (! $result) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials. Email or password is wrong.',
            ], 401);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful',
            'data'    => [
                'user'  => [
                    'id'    => $result['user']->id,
                    'name'  => $result['user']->name,
                    'email' => $result['user']->email,
                ],
                'token' => $result['token'],
            ],
        ], 200);
    }

    /**
     * POST /api/v1/logout
     * Hapus token akses aktif (logout dari device saat ini).
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully',
        ], 200);
    }
}
