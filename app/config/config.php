<?php

use Phalcon\Config;

$config = new Config(
    [
        'database' => [
            'host'     => '127.0.0.1',
            'username' => 'blogging',
            'password' => 'thanhdo',
            'dbname'   => '123456',
        ],

        'application' => [
	        'controllersDir' => "app/controllers/",
	        'modelsDir'      => "app/models/",
            'logDir'         => "log/"
	        'baseUri'        => "/",
        ],
    ]
);

return $config;