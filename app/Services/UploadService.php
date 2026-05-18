<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public function uploadImage(?UploadedFile $file, string $folder, ?string $profile = null): ?string
    {
        if (! $file) {
            return null;
        }

        if ($this->isSvg($file)) {
            return $file->store($folder, 'public');
        }

        return $this->storeOptimizedRasterImage($file, $folder, $profile);
    }

    public function uploadMultipleImages(?array $files, string $folder): array
    {
        if (empty($files)) {
            return [];
        }

        return collect($files)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->map(fn (UploadedFile $file): string => $this->uploadImage($file, $folder))
            ->values()
            ->all();
    }

    public function deleteImage(?string $path): void
    {
        if (blank($path) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    public function deleteImages(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteImage($path);
        }
    }

    private function storeOptimizedRasterImage(UploadedFile $file, string $folder, ?string $profile = null): string
    {
        $imageInfo = getimagesize($file->getRealPath());

        if (! $imageInfo) {
            return $file->store($folder, 'public');
        }

        [$width, $height, $type] = $imageInfo;

        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_WEBP => imagecreatefromwebp($file->getRealPath()),
            default => null,
        };

        if (! $source) {
            return $file->store($folder, 'public');
        }

        $profileConfig = $this->profileFor($folder, $profile);
        $maxWidth = max(1, (int) ($profileConfig['width'] ?? config('uploads.resize_width', 1600)));
        $maxHeight = max(1, (int) ($profileConfig['height'] ?? config('uploads.resize_height', 1600)));
        $quality = max(1, min(100, (int) ($profileConfig['quality'] ?? config('uploads.webp_quality', 84))));
        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        $targetWidth = max(1, (int) round($width * $ratio));
        $targetHeight = max(1, (int) round($height * $ratio));

        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        imagepalettetotruecolor($source);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagefill($target, 0, 0, imagecolorallocatealpha($target, 0, 0, 0, 127));
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        $path = trim($folder, '/').'/'.Str::uuid().'.webp';
        Storage::disk('public')->makeDirectory($folder);
        $stored = imagewebp($target, Storage::disk('public')->path($path), $quality);

        imagedestroy($source);
        imagedestroy($target);

        return $stored ? $path : $file->store($folder, 'public');
    }

    private function profileFor(string $folder, ?string $profile = null): array
    {
        $profiles = config('uploads.profiles', []);
        $folderProfiles = config('uploads.folder_profiles', []);
        $profileName = $profile ?: ($folderProfiles[trim($folder, '/')] ?? 'default');

        return $profiles[$profileName] ?? $profiles['default'] ?? [
            'width' => config('uploads.resize_width', 1600),
            'height' => config('uploads.resize_height', 1600),
            'quality' => config('uploads.webp_quality', 84),
        ];
    }

    private function isSvg(UploadedFile $file): bool
    {
        return $file->getClientOriginalExtension() === 'svg'
            || $file->getMimeType() === 'image/svg+xml';
    }
}
