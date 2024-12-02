<?php

namespace App\Services;

use App\Contracts\Provider\ImageProvider;
use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class ImageService implements ImageProvider
{
    /**
     * @var array<string, array> Cache for indexes
     */
    private array $indexCache = [];

    /**
     * Get the storage path for the index file
     */
    private function getIndexPath(MediaType $type): string
    {
        return $type->getLocation() . '/index.json';
    }

    /**
     * Get the index for a media type
     */
    private function getIndex(MediaType $type): array
    {
        $cacheKey = $type->value;

        if (!isset($this->indexCache[$cacheKey])) {
            $path                        = $this->getIndexPath($type);
            $this->indexCache[$cacheKey] = Storage::disk('public')->exists($path)
                ? json_decode(Storage::disk('public')->get($path), TRUE)
                : [];
        }

        return $this->indexCache[$cacheKey];
    }

    /**
     * Update the index for a media type
     */
    private function updateIndex(array $index, MediaType $type): void
    {
        $this->indexCache[$type->value] = $index;
        Storage::disk('public')->put(
            $this->getIndexPath($type),
            json_encode($index, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Validate an uploaded image
     *
     * @throws InvalidArgumentException
     */
    private function validateImageData(array $image): void
    {
        if (!isset($image['tmp_name'])) {
            throw new InvalidArgumentException('Invalid image upload');
        }
    }

    /**
     * Create index entry for an image
     */
    private function createIndexEntry(
        string $filename,
        array $metadata,
    ): array {
        return [
            'filename'      => $filename,
            'original_name' => $metadata['name'],
            'mime_type'     => $metadata['type'],
            'size'          => $metadata['size'],
            'created_at'    => now()->toIso8601String(),
        ];
    }

    public function storeImage(array $image, MediaType $type): string
    {
        $this->validateImageData($image);

        $id       = Str::uuid()->toString();
        $filename = $id;

        if (!Storage::disk('public')->putFileAs($type->getLocation(), new File($image['tmp_name']), $filename)) {
            throw new RuntimeException('Failed to store image');
        }

        $index      = $this->getIndex($type);
        $index[$id] = $this->createIndexEntry($filename, $image);
        $this->updateIndex($index, $type);

        return $id;
    }

    /**
     * Find an existing image by its source URL
     */
    public function findImageBySourceUrl(string $url, MediaType $type): ?string
    {
        $index     = $this->getIndex($type);
        $searchUrl = basename($url);

        $matches = array_filter($index, function ($data) use ($searchUrl)
        {
            return isset($data['source_url']) && basename($data['source_url']) === $searchUrl;
        });

        if (count($matches) > 0) {
            \Log::info('Found image by source URL: ' . array_key_first($matches));
        }

        return array_key_first($matches) ?: NULL;
    }

    public function downloadImage(string $imageUrl, MediaType $type): string
    {
        try {
            // Check if image already exists
            $existingImageId = $this->findImageBySourceUrl($imageUrl, $type);

            $response = Http::timeout(30)->get($imageUrl);

            if (!$response->successful()) {
                throw new RuntimeException("Failed to download image: HTTP {$response->status()}");
            }

            $tmpPath = tempnam(sys_get_temp_dir(), 'download_');
            $success = file_put_contents($tmpPath, $response->body());

            if ($success === FALSE) {
                throw new RuntimeException('Failed to write to temp file');
            }

            // Get content type and determine extension
            $contentType = $response->header('Content-Type');

            $extension = match ($contentType) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png'               => 'png',
                'image/gif'               => 'gif',
                'image/webp'              => 'webp',
                default                   => 'jpg' // fallback to jpg if unknown
            };

            // Create a filename with the proper extension
            $filename = $existingImageId ?: basename($imageUrl);
            if (!pathinfo($filename, PATHINFO_EXTENSION)) {
                $filename .= '.' . $extension;
            }

            $image = [
                'tmp_name' => $tmpPath,
                'name'     => $filename,
                'type'     => $contentType ?: 'image/jpeg',
                'size'     => strlen($response->body()),
            ];

            try {
                // Store the new image
                $newId = $this->storeImage($image, $type);

                // Add source URL to index
                $index                       = $this->getIndex($type);
                $index[$newId]['source_url'] = $imageUrl;
                $this->updateIndex($index, $type);

                // If we had an existing image, delete it after successful download
                if ($existingImageId !== NULL) {
                    $this->deleteImage($existingImageId, $type);
                }

                return $newId;
            }
            finally {
                // Ensure we clean up the temp file
                if (file_exists($tmpPath)) {
                    unlink($tmpPath);
                }
            }
        }
        catch (\Exception $e) {
            throw new RuntimeException("Failed to download image: {$e->getMessage()}");
        }
    }

    public function getImage(string $id, MediaType $type): string
    {
        $index = $this->getIndex($type);

        if (!isset($index[$id])) {
            throw new RuntimeException("Image not found: {$id}");
        }

        $path = $type->getLocation() . '/' . $index[$id]['filename'];

        if (!Storage::disk('public')->exists($path)) {
            throw new RuntimeException("Image file missing: {$path}");
        }

        return Storage::disk('public')->get($path);
    }

    public function getImageUrl(string $id, MediaType $type): string
    {
        $index = $this->getIndex($type);

        if (!isset($index[$id])) {
            \Log::error("Image not found: {$id}");
            return "";
        }

        $path = $type->getLocation() . '/' . $index[$id]['filename'];

        return Storage::disk('public')->url($path);
    }

    public function deleteImage(string $imageId, MediaType $type): void
    {
        $index = $this->getIndex($type);

        if (isset($index[$imageId])) {
            $path    = $type->getLocation() . '/' . $index[$imageId]['filename'];
            $deleted = Storage::disk('public')->delete($path);

            if (!$deleted) {
                throw new RuntimeException("Failed to delete image file: {$path}");
            }

            unset($index[$imageId]);
            $this->updateIndex($index, $type);
        }
    }
}

