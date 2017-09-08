<?php

$videosCollection = new \Phalcon\Mvc\Micro\Collection();
$videosCollection->setHandler('\App\Controllers\VideosController', true);
$videosCollection->setPrefix('/videos');

// GET METHOD    : Get video infomation
$videosCollection->get('/', 'getVideoListAction');
$videosCollection->get('/{id}', 'getVideoByIdAction');

// PATCH METHOD  : Update if id exict, add new if not
$videosCollection->post('/{id}', 'patchVideoAction');

// DELETE METHOD : Delete video
$videosCollection->delete('/{id}', 'deleteVideoAction');

$app->mount($videosCollection);

// not found URLs
$app->notFound(
  function () use ($app) {
      $exception =
        new \App\Controllers\HttpExceptions\Http404Exception(
          _('URI not found or error in request.'),
          \App\Controllers\AbstractController::ERROR_NOT_FOUND,
          new \Exception('URI not found: ' . $app->request->getMethod() . ' ' . $app->request->getURI())
        );
      throw $exception;
  }
);
