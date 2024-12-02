<?php

namespace App\Contracts\Services;

interface SearchServiceInterface
{
    public function search(string $term, string $token): array;
    public function sanitizeSearchTerm(string $term): string;
    public function formatResults(array $results): array;
}