<?php

use Illuminate\Container\Container;

$maybe_autoload_file_path = dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists($maybe_autoload_file_path)) {
    require $maybe_autoload_file_path;
}

/**
 * @return Container
 */
function doSystem()
{
    static $container;

    if (!isset($container)) {
        if (defined('LARAVEL_START') && function_exists('app')) {
            // Using Laravel framework
            $container = app();
        }
        // and other frameworks..
        else {
            $container = new Container();
        }
    }

    return $container;
}

unset($maybe_autoload_file_path);
