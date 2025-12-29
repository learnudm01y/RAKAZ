<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageCompressionService
{
    protected ImageManager $manager;
    protected int $maxFileSize = 200 * 1024; // 200KB in bytes - more aggressive for WebP
    protected int $initialQuality = 85;
    protected int $minQuality = 50; // WebP maintains quality better at lower settings
    protected int $qualityStep = 5; // Smaller steps for more precise control
    protected int $maxWidth = 1200;
    protected int $maxHeight = 1200;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Compress and store an uploaded image as WebP
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $directory The storage directory (e.g., 'products', 'products/gallery')
     * @return string The stored file path
     */
    public function compressAndStore(UploadedFile $file, string $directory = 'products'): string
    {
        // Read the image
        $image = $this->manager->read($file->getPathname());

        // Resize if larger than max dimensions (maintaining aspect ratio)
        $width = $image->width();
        $height = $image->height();

        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $image->scaleDown($this->maxWidth, $this->maxHeight);
        }

        // Generate unique filename with .webp extension
        $filename = Str::random(40) . '.webp';
        $path = $directory . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        // Ensure directory exists
        $directoryPath = dirname($fullPath);
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        // Start with initial quality and decrease until file size is acceptable
        $quality = $this->initialQuality;

        do {
            // Encode as WebP - much better compression than JPEG
            $encoded = $image->toWebp($quality);
            file_put_contents($fullPath, $encoded);

            $fileSize = filesize($fullPath);

            // If file size is acceptable or we've reached minimum quality, stop
            if ($fileSize <= $this->maxFileSize || $quality <= $this->minQuality) {
                break;
            }

            // Decrease quality for next iteration
            $quality -= $this->qualityStep;

        } while ($quality >= $this->minQuality);

        // If still too large at minimum quality, resize further
        if ($fileSize > $this->maxFileSize && $quality <= $this->minQuality) {
            $this->resizeUntilUnderLimit($image, $fullPath);
        }

        return $path;
    }

    /**
     * Resize image until it's under the file size limit
     */
    protected function resizeUntilUnderLimit($image, string $fullPath): void
    {
        $scaleFactors = [0.9, 0.85, 0.8, 0.75, 0.7, 0.65, 0.6, 0.55, 0.5];

        $originalWidth = $image->width();
        $originalHeight = $image->height();

        foreach ($scaleFactors as $scale) {
            $newWidth = (int)($originalWidth * $scale);
            $newHeight = (int)($originalHeight * $scale);

            $image->resize($newWidth, $newHeight);

            // Use WebP for resized images too
            $encoded = $image->toWebp($this->minQuality);
            file_put_contents($fullPath, $encoded);

            if (filesize($fullPath) <= $this->maxFileSize) {
                break;
            }
        }
    }

    /**
     * Compress and store multiple gallery images as WebP
     *
     * @param array $files Array of UploadedFile objects
     * @param string $directory The storage directory
     * @return array Array of stored file paths
     */
    public function compressAndStoreMultiple(array $files, string $directory = 'products/gallery'): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $paths[] = $this->compressAndStore($file, $directory);
            }
        }

        return $paths;
    }

    /**
     * Set maximum file size in KB
     */
    public function setMaxFileSize(int $kilobytes): self
    {
        $this->maxFileSize = $kilobytes * 1024;
        return $this;
    }

    /**
     * Set maximum dimensions
     */
    public function setMaxDimensions(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }
}
