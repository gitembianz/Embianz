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
     * Directorul in public/ (cu parintii lui). Calea relativa la directorul curent nu e buna:
     * sub Octane directorul curent e vendor/laravel/octane/bin, nu public/.
     */
    public static function makeDirectory($path)
    {
        \Illuminate\Support\Facades\File::ensureDirectoryExists(public_path($path), 0755);
    }

    public static function put($path, $content)
    {
        return file_put_contents(public_path($path), $content);
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
