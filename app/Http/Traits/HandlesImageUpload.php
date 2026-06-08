<?php

namespace App\Http\Traits;

use App\Exceptions\Image\ImageUploadFailedException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUpload
{
    /**
     * Storage disk to use
     */
    protected string $disk = 'public';

    /**
     * Protected images that should never be deleted
     */
    protected array $protectedImages = [
        'images/default.png',
    ];

    /**
     * Upload single image
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string|null $oldImage
     * @return string
     */
    public function uploadImage(
        UploadedFile $file,
        string $folder,
        ?string $oldImage = null
    ): string {
        // 1. Delete old image (smart delete)
        if ($oldImage) {
            $this->deleteImage($oldImage);
        }

        // 2. Generate unique filename
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = Str::uuid() . '.' . $extension;

        // 3. Store file and verify
        $stored = $file->storeAs($folder, $fileName, $this->disk);

        if (!$stored) {
            throw new ImageUploadFailedException();
        }

        // 4. Return relative path for DB
        return $folder . '/' . $fileName;
    }

    /**
     * Upload multiple images
     *
     * @param array<int, UploadedFile> $files
     * @param string $folder
     * @return array<int, string>
     */
    public function uploadMultipleImages(array $files, string $folder): array
    {
        $uploadedPaths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedPaths[] = $this->uploadImage($file, $folder);
            }
        }

        return $uploadedPaths;
    }

    /**
     * Smart delete - protects default images
     */
    public function deleteImage(?string $path): bool
    {
        // 1. No path
        if (!$path) {
            return false;
        }

        // 2. Protect specific default images (exact match)
        if (in_array($path, $this->protectedImages)) {
            return false;
        }

        // 3. Delete if exists
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }

        return false;
    }

    /**
     * Delete multiple images
     *
     * @param array<int, string> $paths
     */
    public function deleteMultipleImages(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteImage($path);
        }
    }

    /**
     * Get image URL
     */
    public function getImageUrl(?string $path, ?string $default = null): string
    {
        if (!$path || in_array($path, $this->protectedImages)) {
            return asset($default ?? 'images/default.png');
        }

        return Storage::disk($this->disk)->url($path);
    }
}