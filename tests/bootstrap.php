<?php

$maybe_autoload_file_path = dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists($maybe_autoload_file_path)) {
    require $maybe_autoload_file_path;
}
