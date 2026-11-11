<?php

namespace App\Services;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageService
{
    protected string $disk = 'local'; // Laravel 12 default local disk is storage/app/private

    public function userBasePath(int $userId): string
    {
        return "memodex/users/{$userId}";
    }

    public function originalsPath(int $userId): string
    {
        return $this->userBasePath($userId) . '/originals';
    }

    public function thumbnailsPath(int $userId): string
    {
        return $this->userBasePath($userId) . '/thumbnails';
    }

    public function cardsPath(int $userId): string
    {
        return $this->userBasePath($userId) . '/cards';
    }

    public function exportsPath(int $userId): string
    {
        return $this->userBasePath($userId) . '/exports';
    }

    /**
     * Store an uploaded photo and generate its thumbnail.
     *
     * @throws \Exception
     */
    public function storeUploadedPhoto(User $user, UploadedFile $file): Photo
    {
        $fileSize = $file->getSize();

        if (!$user->hasStorageFor($fileSize)) {
            throw new \Exception('Storage quota exceeded. Please delete unused photos or cards to free up space.');
        }

        // Validate image mime and dimensions
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $allowedMimes, true)) {
            throw new \Exception('Invalid image format. Only JPEG, PNG, and WebP are allowed.');
        }

        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo) {
            throw new \Exception('Corrupt or invalid image file.');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $fileName = Str::uuid()->toString() . '.' . strtolower($extension);

        // Store original
        $originalPath = $file->storeAs($this->originalsPath($user->id), $fileName, $this->disk);

        // Generate thumbnail (max 400x400 proportional)
        $thumbnailName = 'thumb_' . $fileName;
        $thumbnailPath = $this->generateThumbnail($file->getRealPath(), $user->id, $thumbnailName, $imageInfo[2]);

        // Update user storage quota
        $totalBytes = $fileSize;
        if ($thumbnailPath && Storage::disk($this->disk)->exists($thumbnailPath)) {
            $totalBytes += Storage::disk($this->disk)->size($thumbnailPath);
        }

        $user->increment('storage_used_bytes', $totalBytes);

        return Photo::create([
            'user_id' => $user->id,
            'original_name' => $file->getClientOriginalName(),
            'original_path' => $originalPath,
            'thumbnail_path' => $thumbnailPath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
        ]);
    }

    /**
     * Generate proportional thumbnail using PHP GD.
     */
    protected function generateThumbnail(string $sourcePath, int $userId, string $thumbName, int $imageType): ?string
    {
        if (!extension_loaded('gd')) {
            return null;
        }

        $srcImg = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($sourcePath),
            default => null,
        };

        if (!$srcImg) {
            return null;
        }

        $origW = imagesx($srcImg);
        $origH = imagesy($srcImg);

        $maxDim = 400;
        if ($origW > $origH) {
            $newW = $maxDim;
            $newH = (int) round(($origH / $origW) * $maxDim);
        } else {
            $newH = $maxDim;
            $newW = (int) round(($origW / $origH) * $maxDim);
        }

        $dstImg = imagecreatetruecolor($newW, $newH);

        // Preserve PNG/WebP alpha
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
        imagefilledrectangle($dstImg, 0, 0, $newW, $newH, $transparent);

        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        $relThumbDir = $this->thumbnailsPath($userId);
        $absThumbDir = Storage::disk($this->disk)->path($relThumbDir);
        if (!is_dir($absThumbDir)) {
            mkdir($absThumbDir, 0755, true);
        }

        $absThumbPath = $absThumbDir . '/' . $thumbName;
        imagepng($dstImg, $absThumbPath, 8);

        imagedestroy($srcImg);
        imagedestroy($dstImg);

        return $relThumbDir . '/' . $thumbName;
    }

    /**
     * Delete photo files and decrement storage quota.
     */
    public function deletePhoto(Photo $photo): void
    {
        $bytesFreed = 0;

        if ($photo->original_path && Storage::disk($this->disk)->exists($photo->original_path)) {
            $bytesFreed += Storage::disk($this->disk)->size($photo->original_path);
            Storage::disk($this->disk)->delete($photo->original_path);
        }

        if ($photo->thumbnail_path && Storage::disk($this->disk)->exists($photo->thumbnail_path)) {
            $bytesFreed += Storage::disk($this->disk)->size($photo->thumbnail_path);
            Storage::disk($this->disk)->delete($photo->thumbnail_path);
        }

        if ($bytesFreed > 0 && $photo->user) {
            $photo->user->decrement('storage_used_bytes', min($photo->user->storage_used_bytes, $bytesFreed));
        }

        $photo->delete();
    }

    /**
     * Return private file response with caching and security headers.
     */
    public function servePrivateFile(string $path, ?string $downloadFilename = null): BinaryFileResponse
    {
        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File not found');
        }

        $fullPath = Storage::disk($this->disk)->path($path);

        $headers = [
            'Cache-Control' => 'private, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($downloadFilename) {
            return response()->download($fullPath, $downloadFilename, $headers);
        }

        return response()->file($fullPath, $headers);
    }
}
