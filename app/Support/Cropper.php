<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 2019-03-01
 * Time: 09:57
 */

namespace App\Support;

class Cropper
{
    /**
     * As miniaturas são geradas localmente a partir do disco "public".
     * Quando o arquivo está em um disco remoto (R2/S3), retornamos o caminho
     * original, pois a imagem já é servida publicamente pelo bucket.
     */
    public static function thumb(string $uri, int $width, int $height = null)
    {
        $source = storage_path('app/public/' . ltrim($uri, '/'));
        $cache = public_path('storage/cache');

        if (! is_file($source) || ! is_dir($cache)) {
            return $uri;
        }

        $cropper = new \CoffeeCode\Cropper\Cropper($cache);
        $pathThumb = $cropper->make($source, $width, $height);

        return 'cache/' . collect(explode('/', $pathThumb))->last();
    }

    public static function flush(?string $path)
    {
        $cache = public_path('storage/cache');

        if (! is_dir($cache)) {
            return;
        }

        $cropper = new \CoffeeCode\Cropper\Cropper($cache);

        if (! empty($path)) {
            $cropper->flush($path);
        } else {
            $cropper->flush();
        }
    }
}
