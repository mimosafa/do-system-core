<?php

namespace DoSystem;

$maybe_autoload_file_path = dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists($maybe_autoload_file_path)) {
    require $maybe_autoload_file_path;
}

if (!function_exists(__NAMESPACE__ . '\\app')) {
    /**
     * @return DoSystem\Application\Container
     */
    function app()
    {
        static $app;
        return $app ?: $app = new Application\Container();
    }
}
