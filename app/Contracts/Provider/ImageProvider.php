<?php

namespace App\Contracts\Provider;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;

interface ImageProvider
{

    public function storeImage(array $image, MediaType $type): string;

    public function getImage(string $imageId, MediaType $type): string;

    public function downloadImage(string $imageUrl, MediaType $type): string;

    public function getImageUrl(string $imageId, MediaType $type): string;

    public function deleteImage(string $imageId, MediaType $type): void;
}
