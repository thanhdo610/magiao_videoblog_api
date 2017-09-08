<?php

namespace App\Controllers;

use App\Controllers\HttpExceptions\Http400Exception;
use App\Controllers\HttpExceptions\Http422Exception;
use App\Controllers\HttpExceptions\Http500Exception;
use App\Services\AbstractService;
use App\Services\ServiceException;
use App\Services\VideosService;

/**
*Each function should follow these steps"
*1. Gets and validates request parameters
*2. Prepares data for the service method
*3. Calls the service method
*4. Handles exceptions
*5. Sends the response
*/
class VideosController extends AbstractController
{

	public function getVideoListAction()
	{

		$videosService = new VideosService();

        $data = [];

        $data['name'] = $this->request->getQuery('name', 'string');
        $data['source_video'] = $this->request->getQuery('source_video', 'alphanum');
        $data['category'] = $this->request->getQuery('category', 'alphanum');
        $data['tag'] = $this->request->getQuery('tag', 'alphanum');
        $data['description'] = $this->request->getQuery('description', 'string');
        $data['source_video_id'] = $this->request->getQuery('source_video_id', 'alphanum');
        $data['status'] = $this->request->getQuery('status', 'int');
        $data['_all_status'] = $this->request->getQuery('_all_status', 'int', 1, true);
        $data['_page'] = $this->request->getQuery('_page', 'int', 1, true);
        $data['_size'] = $this->request->getQuery('_size', 'int', 10, true);
        $data['_fetch_all'] = $this->request->getQuery('_fetch_all', 'int');

        try {
            $videoList = $videosService->getVideoList($data);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

        return $videoList;
	}

	public function getVideoByIdAction($id)
	{
		$videosService = new VideosService();
		$errors = [];

	    if (!ctype_alnum($id)) {
            $errors['id'] = 'Id must contain only text and number';
        }

        if ($errors) {
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

		try {
            $video = $videosService->getVideoById($id);
            return [$video];
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }

	}

	public function patchVideoAction($id)
	{
		$videosService = new VideosService();
        $errors = [];
        $data = [];

	    if (!ctype_alnum($id)) {
            $errors['id'] = 'Id must contain only text and number';
        }

        if ($errors) {
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

        $data['name'] = $this->request->getPost('name', 'string');
        $data['source_video'] = $this->request->getPost('source_video', 'alphanum');
        $data['category'] = $this->request->getPost('category', 'alphanum');
        $data['tag'] = $this->request->getPost('tag', 'alphanum');
        $data['description'] = $this->request->getPost('description', 'string');
        $data['source_video_id'] = $this->request->getPost('source_video_id', 'alphanum');
        $data['source_full_url'] = $this->request->getPost('source_full_url', 'alphanum');
        $data['status'] = $this->request->getPost('status', 'int');

        try {
            return $videosService->saveVideo($id, $data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case VideosService::ERROR_UNABLE_CREATE_VIDEO:
                case VideosService::ERROR_UNABLE_UPDATE_VIDEO:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
	}

	public function deleteVideoAction($id)
	{
		$videosService = new VideosService();
		$errors = [];

		if (!ctype_alnum($id)) {
            $errors['id'] = 'Id must contain only text and number';
        }

        if ($errors) {
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

		try {
            $videosService->deleteVideo($id);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case VideosService::ERROR_UNABLE_DELETE_VIDEO:
                case VideosService::ERROR_VIDEO_NOT_FOUND:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
	}

}

