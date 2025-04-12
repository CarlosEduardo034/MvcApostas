<?php
spl_autoload_register(function ($class) {
    $base_dir = dirname(__DIR__);

    $paths = [
        $base_dir . '/app/controllers/',
        $base_dir . '/app/models/',
        $base_dir . '/core/',
        $base_dir . '/config/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    throw new Exception("Classe '$class' não encontrada.");
});
