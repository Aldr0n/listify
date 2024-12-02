<?php

namespace App\Contracts\Services;

interface SearchServiceInterface
{
    /**
     * Execute search with given term
     * @param string $term
     * @param string $token
     * @return array
     */
    public function search(string $term, string $token): array;

    /**
     * Clean and format search term
     * @param string $term
     * @return string
     */
    public function sanitizeSearchTerm(string $term): string;

    /**
     * Format raw search results
     * @param array $results
     * @return array
     */
    public function formatResults(array $results): array;
}