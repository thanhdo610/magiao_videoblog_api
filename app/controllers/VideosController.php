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
        $data['source_video'] = $this->request->getQuery('source_video', 'string');
        $data['category'] = $this->request->getQuery('category', 'string');
        $data['tag'] = $this->request->getQuery('tag', 'string');
        $data['description'] = $this->request->getQuery('description', 'string');
        $data['source_video_id'] = $this->request->getQuery('source_video_id', 'alphanum');
        $data['source_full_url'] = $this->request->getQuery('source_full_url', 'string');
        $data['keyword'] = $this->request->getQuery('keyword', 'string');
        $data['lengthgreaterthan'] = $this->request->getQuery('lengthgreaterthan', 'int');
        $data['lengthlessthan'] = $this->request->getQuery('lengthlessthan', 'int');
        $data['status'] = $this->request->getQuery('status', 'int', 20000, true);
        $data['_all_status'] = $this->request->getQuery('_all_status', 'int');
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
        $data = [];

	    if (!ctype_alnum($id)) {
            $errors['id'] = 'Id must contain only text and number';
        }

        $data['_all_status'] = $this->request->getQuery('_all_status', 'int');
        $data['_fetch_all'] = $this->request->getQuery('_fetch_all', 'int');

        if ($errors) {
            $exception = new Http400Exception(_('Input parameters validation error'), self::ERROR_INVALID_REQUEST);
            throw $exception->addErrorDetails($errors);
        }

		try {
            $video = $videosService->getVideoById($id, $data);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $video;

	}

    public function getVideoByKeywordAction($keyword)
    {
        $videosService = new VideosService();
        $data = [];

        $data['_all_status'] = $this->request->getQuery('_all_status', 'int');
        $data['_fetch_all'] = $this->request->getQuery('_fetch_all', 'int');

        try {
            $videoList = $videosService->getVideoByKeyword($keyword);
        } catch (ServiceException $e) {
            throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
        }
        return $videoList;
    }

	public function createVideoAction($id)
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
        $data['source_video'] = $this->request->getPost('source_video', 'string');
        $data['category'] = $this->request->getPost('category', 'string');
        $data['tag'] = $this->request->getPost('tag', 'string');
        $data['description'] = $this->request->getPost('description', 'string');
        $data['source_video_id'] = $this->request->getPost('source_video_id', 'alphanum');
        $data['source_full_url'] = $this->request->getPost('source_full_url', 'string');
        $data['keyword'] = $this->request->getPost('keyword', 'string');
        $data['length'] = $this->request->getPost('length', 'int');
        $data['status'] = $this->request->getPost('status', 'int', 20000, true);
        $data['published_at'] = $this->request->getPost('published_at');

        try {
            return $videosService->saveVideo($id, $data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case VideosService::ERROR_UNABLE_CREATE_VIDEO:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception(_('Internal Server Error'), $e->getCode(), $e);
            }
        }
	}

    public function updateVideoAction($id)
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
        $data['source_video'] = $this->request->getPost('source_video', 'string');
        $data['category'] = $this->request->getPost('category', 'string');
        $data['tag'] = $this->request->getPost('tag', 'string');
        $data['description'] = $this->request->getPost('description', 'string');
        $data['source_video_id'] = $this->request->getPost('source_video_id', 'alphanum');
        $data['source_full_url'] = $this->request->getPost('source_full_url', 'string');
        $data['keyword'] = $this->request->getPost('keyword', 'string');
        $data['length'] = $this->request->getPost('length', 'int');
        $data['status'] = $this->request->getPost('status', 'int');
        $data['published_at'] = $this->request->getPost('published_at');

        try {
            return $videosService->updateVideo($id, $data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
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
            return $videosService->deleteVideoForever($id);
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

