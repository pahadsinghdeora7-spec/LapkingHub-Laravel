<?php

if (! function_exists('app_support_path')) {
    /**
     * Resolve a path relative to the application support directory.
     */
    function app_support_path(string $path = ''): string
    {
        $basePath = app_path('Support');

        return $path === '' ? $basePath : $basePath.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
    }
}
