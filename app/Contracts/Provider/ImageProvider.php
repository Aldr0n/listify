<?php

namespace App\Contracts\Provider;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;

interface ImageProvider
{
    /**
     * Store uploaded image file
     * @param array $image
     * @param MediaType $type
     * @return string Image ID
     */
    public function storeImage(array $image, MediaType $type): string;

    /**
     * Get image contents by ID
     * @param string $imageId
     * @param MediaType $type
     * @return string
     */
    public function getImage(string $imageId, MediaType $type): string;

    /**
     * Download and store remote image
     * @param string $imageUrl
     * @param MediaType $type
     * @return string Image ID
     */
    public function downloadImage(string $imageUrl, MediaType $type): string;

    /**
     * Get public URL for image
     * @param string $imageId
     * @param MediaType $type
     * @return string
     */
    public function getImageUrl(string $imageId, MediaType $type): string;

    /**
     * Delete image and its index entry
     * @param string $imageId
     * @param MediaType $type
     * @return void
     */
    public function deleteImage(string $imageId, MediaType $type): void;
}
