<?php

namespace core;

class Cache
{
    private static $defaultTtl = 30; // Tempo padrão em segundos
    private static $cacheDir = __DIR__ . '/../cache/';

    public static function get(string $key)
    {
        $file = self::getCacheFilePath($key);
        if (!file_exists($file)) return false;

        $data = json_decode(file_get_contents($file), true);

        if (!$data || !isset($data['expires_at']) || time() > $data['expires_at']) {
            unlink($file);
            return false;
        }

        return $data['value'];
    }

    public static function getOrUpdate(string $key, callable $fallback, int $ttl = null)
    {
        $file = self::getCacheFilePath($key);
        $ttl = $ttl ?? self::$defaultTtl;
        $expiredData = null;

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);

            if ($data && isset($data['expires_at']) && isset($data['value'])) {
                if (time() <= $data['expires_at']) {
                    return $data['value'];
                }

                $createdAt = $data['created_at'];
                $expiredData = $data['value'];
            }
        }

        // Tenta atualizar o cache
        try {
            $newValue = $fallback(); // Executa a função que busca os dados atualizados
            self::set($key, $newValue, $ttl);
            return $newValue;
        } catch (\Throwable $e) {
            if ($expiredData !== null) {
                self::set($key, $expiredData, 60, $createdAt);
                return $expiredData;
            }

            return false;
        }
    }


    public static function set(string $key, $value, int $ttl = null, $createdAt = null): bool
    {
        $ttl = $ttl ?? self::$defaultTtl;
        $createdAt = $createdAt ?? time();

        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }

        $data = [
            'created_at' => $createdAt,
            'expires_at' => time() + $ttl,
            'value' => $value
        ];
        $json = json_encode($data, JSON_INVALID_UTF8_IGNORE);

        return file_put_contents(self::getCacheFilePath($key), $json) !== false;
    }

    public static function delete(string $key): void
    {
        $file = self::getCacheFilePath($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private static function getCacheFilePath(string $key): string
    {
        return self::$cacheDir . md5($key) . '.cache.json';
    }

    public static function getCreatedAt(string $key): ?int
    {
        $file = self::getCacheFilePath($key);

        if (!file_exists($file)) return null;

        $data = json_decode(file_get_contents($file), true);

        return $data['created_at'] ?? null;
    }
}
