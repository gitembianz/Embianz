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

    public static function put($path, $content)
    {
        return file_put_contents(public_path($path), $content);
    }

    public static function get($path)
    {
        return file_get_contents(public_path($path));
    }
}
