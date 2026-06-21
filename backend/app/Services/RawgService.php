<?php

namespace App\Services;

use App\Contracts\RawgServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RawgService implements RawgServiceInterface
{
    /**
     * Cari game dari RAWG API.
     *
     * @param string $query
     * @param int $page
     * @return array
     */
    public function searchGames(string $query, int $page = 1): array
    {
        $apiKey = config('services.rawg.key');
        
        if (empty($apiKey)) {
            Log::error('RAWG API Key is not configured.');
            return [
                'status' => 'error',
                'message' => 'Internal server error: Missing API Key'
            ];
        }

        try {
            $response = Http::get('https://api.rawg.io/api/games', [
                'key' => $apiKey,
                'search' => $query,
                'page_size' => 20,
                'page' => $page,
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json(),
                ];
            }

            Log::error('RAWG API request failed: ' . $response->body());
            
            return [
                'status' => 'error',
                'message' => 'Failed to fetch data from RAWG.io'
            ];

        } catch (\Exception $e) {
            Log::error('Exception when calling RAWG API: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => 'Failed to fetch data from RAWG.io'
            ];
        }
    }
}
