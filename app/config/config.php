<?php

use Phalcon\Config;

$config = new Config(
    [
        'database' => [
            'adapter'  => 'Mysql',
            'host'     => '127.0.0.1',
            'username' => 'thanhdo',
            'password' => '123456',
            'dbname'   => 'blogging',
        ],

        'application' => [
	        'controllersDir' => "app/controllers/",
	        'modelsDir'      => "app/models/",
            'logDir'         => "log/",
	        'baseUri'        => "/",
        ],
    ]
);

return $config;