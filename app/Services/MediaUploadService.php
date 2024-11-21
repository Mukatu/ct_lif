<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaUploadService
{
    public function uploadMedia(UploadedFile $file, string $folder): array
    {
        $path = $file->store($folder);
        $type = $file->getMimeType();

        // Si c'est une image, créer une miniature
        if (str_starts_with($type, 'image/')) {
            $thumbnail = $this->createThumbnail($file, $folder);
        }

        return [
            'path' => $path,
            'type' => $type,
            'thumbnail_path' => $thumbnail ?? null
        ];
    }

    public function deleteMedia(string $path): bool
    {
        if (Storage::exists($path)) {
            Storage::delete($path);

            // Supprimer la miniature si elle existe
            $thumbnailPath = $this->getThumbnailPath($path);
            if (Storage::exists($thumbnailPath)) {
                Storage::delete($thumbnailPath);
            }

            return true;
        }

        return false;
    }

    private function createThumbnail(UploadedFile $file, string $folder): string
    {
        $image = Image::make($file);
        $thumbnail = $image->fit(300, 300);

        $thumbnailPath = $folder . '/thumbnails/' . $file->hashName();
        Storage::put($thumbnailPath, (string) $thumbnail->encode());

        return $thumbnailPath;
    }

    private function getThumbnailPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
    }
}