<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Ajuda a lidar com arquivos no disco padrão (R2 em produção).
 *
 * `Storage::disk()->exists()` no R2 é uma chamada HTTP (HeadObject) e custa
 * centenas de milissegundos. Como as views chamam `cover()`/`getMetaImg()` em
 * cada item, sem cache uma única página dispara dezenas de requisições ao R2.
 */
class Media
{
    /**
     * Tempo de cache da checagem de existência, em horas.
     */
    private const CACHE_HORAS = 6;

    public static function exists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // Em testes (local) a checagem é barata e o cache poderia mascarar
        // arquivos criados/removidos dentro do mesmo teste.
        if (config('filesystems.default') === 'local') {
            return Storage::disk()->exists($path);
        }

        $disk = config('filesystems.default');
        $root = config("filesystems.disks.{$disk}.root");
        $key = 'media:exists:' . sha1($disk . '|' . $root . '|' . $path);

        return Cache::remember($key, now()->addHours(self::CACHE_HORAS), function () use ($path) {
            return Storage::disk()->exists($path);
        });
    }
}
