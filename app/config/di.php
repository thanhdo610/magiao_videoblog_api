<?php

use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Di\FactoryDefault;

// Initializing a DI Container
$di = new \Phalcon\DI\FactoryDefault();

/**
 * Overriding Response-object to set the Content-type header globally
 */
$di->setShared(
  'response',
  function () {
      $response = new \Phalcon\Http\Response();
      $response->setContentType('application/json', 'utf-8');

      return $response;
  }
);

/** Common config */
$di->setShared('config', $config);

$di->set(
    'db',
    function () use ($config){
      // return new PdoMysql(
      //     [
      //         "host"     => $config->database->host,
      //         "username" => $config->database->username,
      //         "password" => $config->database->password,
      //         "dbname"   => $config->database->dbname,
      //     ]
      // );

      $connection = new PdoMysql(array(
        "host"     => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname"   => $config->database->dbname,
      ));

      $eventsManager = new Phalcon\Events\Manager();

      // $logger = new \Phalcon\Logger\Adapter\File(__DIR__ . '/../../log/' . date("Y-m-d") . 'db.log');
      $logger = new \Phalcon\Logger\Adapter\File('/var/www/magiao_videoblog_api/app/log/' . date("Y-m-d") . 'db.log');

       //Listen all the database events
      $eventsManager->attach('db', function($event, $connection) use ($logger) {
         if ($event->getType() == 'beforeQuery') {
              $sqlVariables = $connection->getSQLVariables();
              if (count($sqlVariables)) {
                  $logger->info($connection->getSQLStatement() . ' ' . join(', ', $sqlVariables));
              } else {
                  $logger->info($connection->getSQLStatement());
              }
          }
      });

      //Assign the eventsManager to the db adapter instance
      $connection->setEventsManager($eventsManager);

      return $connection;
    });



return $di;
