<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    public function upload(?UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        return Storage::disk('public')->url($path);
    }

    public function uploadMultiple(array $files, string $folder = 'uploads'): array
    {
        $urls = [];
        foreach ($files as $file) {
            if ($url = $this->upload($file, $folder)) {
                $urls[] = $url;
            }
        }
        return $urls;
    }
}
