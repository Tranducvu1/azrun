<?php

namespace App\Support;

class ImageUrl
{
    public static function resolve(?string $path, string $fallback = ''): string
    {
        if (!$path) {
            return $fallback ?: 'https://placehold.co/600x600/fff7ed/f97316?text=SportShop';
        }

        if (preg_match('#^https?://#i', $path)) {
            if (preg_match('#(/storage/.+)$#', $path, $matches)) {
                return asset($matches[1]);
            }

            return $path;
        }

        if (str_starts_with($path, '/storage')) {
            return asset($path);
        }

        if (str_starts_with($path, 'storage/')) {
            return asset('/' . $path);
        }

        return $path;
    }

    /** @param  array<int, string|null>  $paths */
    public static function resolveMany(array $paths, string $fallback = ''): array
    {
        $resolved = [];

        foreach ($paths as $path) {
            $url = self::resolve($path, '');
            if ($url && !in_array($url, $resolved, true)) {
                $resolved[] = $url;
            }
        }

        return $resolved ?: [self::resolve(null, $fallback)];
    }

    /** Normalize any stored path to /storage/... for DB persistence. */
    public static function normalize(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (preg_match('#^https?://#i', $path) && preg_match('#(/storage/.+)$#', $path, $matches)) {
            return $matches[1];
        }

        if (str_starts_with($path, '/storage')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        return $path;
    }

}
