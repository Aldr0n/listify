<?php

namespace App\Contracts\Provider;

interface ImageProvider
{
    /**
     * Store an image 
     */
    public function storeImage(array $image, string $userId = NULL);

    /**
     * Get an image
     */
    public function getImage(string $imageId);
}
