<?php

use Phalcon\Loader;

// Creates the autoloader
$loader = new Loader();

// Register some namespaces
$loader->registerNamespaces(
    [
        'App\Services'    => realpath(__DIR__ . '/../services/'),
	    'App\Controllers' => realpath(__DIR__ . '/../controllers/'),
	    'App\Models'      => realpath(__DIR__ . '/../models/'),
    ]
);

// Register autoloader
$loader->register();