<?php

namespace App\Contracts;

/**
 * Interface RawgServiceInterface
 *
 * Kontrak untuk operasi pencarian game melalui RAWG API.
 */
interface RawgServiceInterface
{
    /**
     * Cari game dari RAWG API.
     *
     * @param string $query
     * @param int $page
     * @return array
     */
    public function searchGames(string $query, int $page): array;
}
