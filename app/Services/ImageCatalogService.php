<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCatalogService
{
    private string $sourceDir;

    public function __construct()
    {
        $this->sourceDir = base_path('image');
    }

    /**
     * Copy source image(s) to public storage and return web URLs.
     *
     * @param  string|array<int, string>  $sources  Filename(s) inside azrun/image/
     * @return array{thumbnail: string, images: array<int, string>}
     */
    public function publishForProduct(string $productSlug, string|array $sources): array
    {
        $files = is_array($sources) ? array_values($sources) : [$sources];
        $urls = [];

        foreach ($files as $file) {
            $url = $this->publish($productSlug, $file);
            if ($url) {
                $urls[] = $url;
            }
        }

        if (empty($urls)) {
            $fallback = 'https://placehold.co/800x800/fff7ed/f97316?text=' . urlencode('AZ RUN');
            return ['thumbnail' => $fallback, 'images' => []];
        }

        return [
            'thumbnail' => $urls[0],
            'images' => count($urls) > 1 ? array_slice($urls, 1) : [],
        ];
    }

    public function publish(string $folder, string $filename): ?string
    {
        $source = $this->sourceDir . '/' . $filename;
        if (!File::exists($source)) {
            return null;
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION) ?: 'jpg');
        $destName = Str::slug(pathinfo($filename, PATHINFO_FILENAME)) . '.' . $ext;
        $destPath = "catalog/{$folder}/{$destName}";

        Storage::disk('public')->put($destPath, File::get($source));

        return '/storage/' . ltrim($destPath, '/');
    }
}
