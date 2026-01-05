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

    // Thumbnail settings for hover and color images (max 20KB)
    protected int $thumbnailMaxFileSize = 20 * 1024; // 20KB
    protected int $thumbnailInitialQuality = 75;
    protected int $thumbnailMinQuality = 30;
    protected int $thumbnailMaxWidth = 600;
    protected int $thumbnailMaxHeight = 600;

    // Mobile-optimized settings for phones (max 15KB)
    protected int $mobileMaxFileSize = 15 * 1024; // 15KB - strict limit for mobile devices
    protected int $mobileInitialQuality = 65;
    protected int $mobileMinQuality = 25;
    protected int $mobileMaxWidth = 480;
    protected int $mobileMaxHeight = 480;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Compress and store an uploaded image as WebP
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $directory The storage directory (e.g., 'products', 'products/gallery')
     * @param bool $isThumbnail Whether to use aggressive thumbnail compression (max 20KB)
     * @return string The stored file path
     */
    public function compressAndStore(UploadedFile $file, string $directory = 'products', bool $isThumbnail = false): string
    {
        // Read the image
        $image = $this->manager->read($file->getPathname());

        // Use thumbnail settings if specified
        $maxWidth = $isThumbnail ? $this->thumbnailMaxWidth : $this->maxWidth;
        $maxHeight = $isThumbnail ? $this->thumbnailMaxHeight : $this->maxHeight;
        $maxFileSize = $isThumbnail ? $this->thumbnailMaxFileSize : $this->maxFileSize;
        $initialQuality = $isThumbnail ? $this->thumbnailInitialQuality : $this->initialQuality;
        $minQuality = $isThumbnail ? $this->thumbnailMinQuality : $this->minQuality;

        // Resize if larger than max dimensions (maintaining aspect ratio)
        $width = $image->width();
        $height = $image->height();

        if ($width > $maxWidth || $height > $maxHeight) {
            $image->scaleDown($maxWidth, $maxHeight);
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
        $quality = $initialQuality;

        do {
            // Encode as WebP - much better compression than JPEG
            $encoded = $image->toWebp($quality);
            file_put_contents($fullPath, $encoded);

            $fileSize = filesize($fullPath);

            // If file size is acceptable or we've reached minimum quality, stop
            if ($fileSize <= $maxFileSize || $quality <= $minQuality) {
                break;
            }

            // Decrease quality for next iteration
            $quality -= $this->qualityStep;

        } while ($quality >= $minQuality);

        // If still too large at minimum quality, resize further
        if ($fileSize > $maxFileSize && $quality <= $minQuality) {
            $this->resizeUntilUnderLimit($image, $fullPath, $maxFileSize, $minQuality);
        }

        return $path;
    }

    /**
     * Resize image until it's under the file size limit
     */
    protected function resizeUntilUnderLimit($image, string $fullPath, int $maxFileSize = null, int $minQuality = null): void
    {
        $maxFileSize = $maxFileSize ?? $this->maxFileSize;
        $minQuality = $minQuality ?? $this->minQuality;

        $scaleFactors = [0.9, 0.85, 0.8, 0.75, 0.7, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4, 0.35, 0.3];

        $originalWidth = $image->width();
        $originalHeight = $image->height();

        foreach ($scaleFactors as $scale) {
            $newWidth = (int)($originalWidth * $scale);
            $newHeight = (int)($originalHeight * $scale);

            $image->resize($newWidth, $newHeight);

            // Use WebP for resized images too
            $encoded = $image->toWebp($minQuality);
            file_put_contents($fullPath, $encoded);

            if (filesize($fullPath) <= $maxFileSize) {
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

    /**
     * Compress and store a mobile-optimized version of the image (max 15KB)
     * This creates a smaller version specifically for mobile devices
     *
     * @param UploadedFile|string $file The uploaded image file or existing file path
     * @param string $directory The storage directory (e.g., 'products/mobile')
     * @return string The stored file path
     */
    public function compressAndStoreForMobile(UploadedFile|string $file, string $directory = 'products/mobile'): string
    {
        // Read the image from either uploaded file or existing path
        if ($file instanceof UploadedFile) {
            $image = $this->manager->read($file->getPathname());
        } else {
            // It's a path to an existing file
            $fullFilePath = storage_path('app/public/' . $file);
            if (!file_exists($fullFilePath)) {
                throw new \Exception("Source file not found: {$file}");
            }
            $image = $this->manager->read($fullFilePath);
        }

        // Use mobile settings - very aggressive compression
        $maxWidth = $this->mobileMaxWidth;
        $maxHeight = $this->mobileMaxHeight;
        $maxFileSize = $this->mobileMaxFileSize;
        $initialQuality = $this->mobileInitialQuality;
        $minQuality = $this->mobileMinQuality;

        // Resize if larger than max dimensions (maintaining aspect ratio)
        $width = $image->width();
        $height = $image->height();

        if ($width > $maxWidth || $height > $maxHeight) {
            $image->scaleDown($maxWidth, $maxHeight);
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
        $quality = $initialQuality;

        do {
            // Encode as WebP - much better compression than JPEG
            $encoded = $image->toWebp($quality);
            file_put_contents($fullPath, $encoded);

            $fileSize = filesize($fullPath);

            // If file size is acceptable or we've reached minimum quality, stop
            if ($fileSize <= $maxFileSize || $quality <= $minQuality) {
                break;
            }

            // Decrease quality for next iteration
            $quality -= $this->qualityStep;

        } while ($quality >= $minQuality);

        // If still too large at minimum quality, resize further
        if ($fileSize > $maxFileSize && $quality <= $minQuality) {
            $this->resizeUntilUnderLimit($image, $fullPath, $maxFileSize, $minQuality);
        }

        return $path;
    }

    /**
     * Compress and store multiple gallery images for mobile (max 15KB each)
     *
     * @param array $files Array of UploadedFile objects or existing file paths
     * @param string $directory The storage directory
     * @return array Array of stored file paths
     */
    public function compressAndStoreMultipleForMobile(array $files, string $directory = 'products/mobile/gallery'): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $paths[] = $this->compressAndStoreForMobile($file, $directory);
            } elseif (is_string($file)) {
                // It's an existing file path
                $paths[] = $this->compressAndStoreForMobile($file, $directory);
            }
        }

        return $paths;
    }

    /**
     * Create mobile version from an already stored desktop image path
     *
     * @param string $desktopImagePath The path to the desktop image (relative to public storage)
     * @param string $mobileDirectory The directory to store mobile version
     * @return string|null The mobile image path or null if source doesn't exist
     */
    public function createMobileVersionFromExisting(string $desktopImagePath, string $mobileDirectory = 'products/mobile'): ?string
    {
        $fullPath = storage_path('app/public/' . $desktopImagePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        return $this->compressAndStoreForMobile($desktopImagePath, $mobileDirectory);
    }
}
