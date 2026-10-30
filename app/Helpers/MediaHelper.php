<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    public static function delete($path)
    {
        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
            clearstatcache();
        }
    }

    public static function exists($path)
    {
        return file_exists(public_path($path));
    }

    /**
     * Directorul in public/, cu parintii lui si cu drepturi de scriere pentru server si pentru deploy (0775).
     * Calea relativa la directorul curent nu e buna: sub Octane directorul curent e vendor/laravel/octane/bin, nu public/.
     */
    public static function makeDirectory($path)
    {
        $current = rtrim(public_path(), '/');

        foreach (array_filter(explode('/', trim($path, '/')), 'strlen') as $part) {
            $current .= '/' . $part;

            if (is_dir($current)) {
                continue;
            }

            // Doua cereri pot crea acelasi director in acelasi timp: conteaza sa existe la final
            if (!@mkdir($current, 0775) && !is_dir($current)) {
                throw new \RuntimeException("Cannot create the folder $path. Check the permissions of " . dirname($current));
            }

            // mkdir tine cont de umask
            @chmod($current, 0775);
        }
    }

    public static function put($path, $content)
    {
        $written = file_put_contents(public_path($path), $content);

        if ($written !== false) {
            @chmod(public_path($path), 0664);
        }

        return $written;
    }

    public static function get($path)
    {
        return file_get_contents(public_path($path));
    }
    
     public static function size($path)
    {
        $fullPath = public_path($path);
        return file_exists($fullPath) ? filesize($fullPath) : 0;
    }
}
