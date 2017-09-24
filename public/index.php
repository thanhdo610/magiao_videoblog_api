<?php

use App\Controllers\AbstractHttpException;

try {
	// Loading Configs
	$config = require(__DIR__ . '/../app/config/config.php');

	// Autoloading classes
	require __DIR__ . '/../app/config/loader.php';

	require __DIR__ . '/../app/config/logger.php';

	// Initializing DI container
	/** @var \Phalcon\DI\FactoryDefault $di */
	$di = require __DIR__ . '/../app/config/di.php';

	// Initializing application
	$app = new \Phalcon\Mvc\Micro();

	// Setting DI container
	$app->setDI($di);

	// Setting up routing
	require __DIR__ . '/../app/config/routes.php';

	$logger->info(sprintf("CLIENT --- [%s] [%s] [%s] Ref [%s]",
        $app->request->getClientAddress(),
        $app->request->getMethod(),
        $app->request->getURI(),
        $app->request->getHTTPReferer()
    ));

	// Making the correct answer after executing
	$app->after(
		function () use ($app) {

			// Getting the return value of method
			$return = $app->getReturnedValue();

			if (is_array($return)) {
				// Transforming arrays to JSON
				$app->response->setContent(json_encode($return));
			} elseif (!strlen($return)) {
				// Successful response without any content
				$app->response->setStatusCode('204', 'No Content');
			} else {
				// Unexpected response
				throw new Exception('Bad Response');
			}

			// Sending response to the client
			$app->response->send();
		}
	);

	// Processing request
	$app->handle();
} catch (AbstractHttpException $e) {
	$logger->error($e->getCode() . ' : ' . $e->getMessage());
	$response = $app->response;
	$response->setStatusCode($e->getCode(), $e->getMessage());
	$response->setJsonContent($e->getAppError());
	$response->send();
} catch (\Phalcon\Http\Request\Exception $e) {
	$logger->error('400 Bad Request : ' . $e->getMessage());
	$app->response->setStatusCode(400, 'Bad request')
	              ->setJsonContent([
		              AbstractHttpException::KEY_CODE    => 400,
		              AbstractHttpException::KEY_MESSAGE => 'Bad request'
	              ])
	              ->send();
} catch (\Exception $e) {
	$logger->critical($e->getMessage());
	// Standard error format
	$result = [
		AbstractHttpException::KEY_CODE    => 500,
		AbstractHttpException::KEY_MESSAGE => 'Some error occurred on the server.'
	];

	// Sending error response
	$app->response->setStatusCode(500, 'Internal Server Error')
	              ->setJsonContent($result)
	              ->send();
}