<?php
namespace App\Services;

use App\Models\Videos;

// Videos Database design
// name 				: video name, normally same as source video name
// source_video			: source of video(youtube, xvideos, dailymotion ...) lowercase
// category 			: video category, can not be blank
// tag 					: video tag, can be blank
// description 			: video description, can be blank
// source_video_id 		: id of video from main source
// source_full_url 		: full url of video from main source
// status 				: video state, NORMAL = 100, PENDING = 101, DELETED = 102. Default is NORMAL
// keyword 				: video keyword
// length 				: video length (by second)
// _id 					: design as md5(source_video . source_video_id)
// _created_at 			: added time
// _updated_at 			: updated time

/**
 * Business-logic for videos
 *
 * Class VideosService
 */
class VideosService extends AbstractService{
	/** Video NORMAL state */
	const STATUS_NORMAL_VIDEO = 20000;

	/** Video NORMAL state */
	const STATUS_PENDING_VIDEO = 20001;

	/** Video NORMAL state */
	const STATUS_DEACTIVED_VIDEO = 20002;

	/** Unable to create video */
	const ERROR_UNABLE_CREATE_VIDEO = 11001;

	/** Video not found */
	const ERROR_VIDEO_NOT_FOUND = 11002;

	/** No such video */
	const ERROR_INCORRECT_VIDEO = 11003;

	/** Unable to update video */
	const ERROR_UNABLE_UPDATE_VIDEO = 11004;

	/** Unable to delete video */
	const ERROR_UNABLE_DELETE_VIDEO = 11005;

	/** Video already exists */
	const ERROR_ALREADY_EXISTS = 11006;

	/** Video not exists */
	const ERROR_NOT_EXISTS = 11007;

	const FETCH_NOT_ALL_FIELDS = "name, source_video, category, tag, description, source_video_id, source_full_url, keyword, length, published_at";
	const FETCH_ALL_FIELDS = "name, source_video, category, tag, description, source_video_id, source_full_url, keyword, length, published_at, status, _id, _created_at, _updated_at";

	/**
	* Creating a new video
	*
	* @param array $videoData
	*/
	public function saveVideo($_id, array $videoData)
	{
		try {
			$video   = new Videos();

			$videoData['name'] 				= (is_null($videoData['name'])) ? "-" : $videoData['name'];
			$videoData['source_video'] 		= (is_null($videoData['source_video'])) ? "-" : $videoData['source_video'];
			$videoData['category'] 			= (is_null($videoData['category'])) ? "-" : $videoData['category'];
			$videoData['tag'] 				= (is_null($videoData['tag'])) ? "-" : $videoData['tag'];
			$videoData['description'] 		= (is_null($videoData['description'])) ? "-" : $videoData['description'];
			$videoData['source_video_id'] 	= (is_null($videoData['source_video_id'])) ? "-" : $videoData['source_video_id'];
			$videoData['source_full_url'] 	= (is_null($videoData['source_full_url'])) ? "-" : $videoData['source_full_url'];
			$videoData['keyword'] 			= (is_null($videoData['keyword'])) ? "-" : $videoData['keyword'];
			$videoData['length'] 			= (is_null($videoData['keyword'])) ? 200 : $videoData['length'];
			$videoData['status'] 			= (is_null($videoData['status'])) ? self::STATUS_NORMAL_VIDEO : $videoData['status'];
			$videoData['published_at'] 			= (is_null($videoData['published_at'])) ? date('Y-m-d H:i:s') : $videoData['published_at'];

			$result = $video->setId($_id)
				->setName($videoData['name'])
				->setSourceVideo($videoData['source_video'])
				->setCategory($videoData['category'])
				->setTag($videoData['tag'])
				->setDescription($videoData['description'])
				->setSourceVideoId($videoData['source_video_id'])
				->setSourceFullUrl($videoData['source_full_url'])
				->setKeyword($videoData['keyword'])
				->setLength($videoData['length'])
				->setStatus($videoData['status'])
				->setPublishedAt($videoData['published_at'])
				->create();

			if (!$result) {
				throw new ServiceException('Unable to create video', self::ERROR_UNABLE_CREATE_VIDEO);
			} else {
				return self::getVideoById($_id);
			}

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	* Updating video
	*
	* @param array $videoData
	*/
	public function updateVideo($_id, array $videoData)
	{
		try {
			$video = Videos::findFirst(
				[
					'conditions' => '_id = :_id:',
					'bind'       => [
						'_id' => $_id
					],
				]
			);

			if (!$video) {
				throw new ServiceException("Video not found", self::ERROR_VIDEO_NOT_FOUND);
			}

			if (!is_null($videoData['name'])) $video->setName($videoData['name']);
			if (!is_null($videoData['source_video'])) $video->setSourceVideo($videoData['source_video']);
			if (!is_null($videoData['category'])) $video->setCategory($videoData['category']);
			if (!is_null($videoData['tag'])) $video->setTag($videoData['tag']);
			if (!is_null($videoData['description'])) $video->setDescription($videoData['description']);
			if (!is_null($videoData['source_video_id'])) $video->setSourceVideoId($videoData['source_video_id']);
			if (!is_null($videoData['source_full_url'])) $video->setSourceFullUrl($videoData['source_full_url']);
			if (!is_null($videoData['keyword'])) $video->setKeyword($videoData['keyword']);
			if (!is_null($videoData['length'])) $video->setLength($videoData['length']);
			if (!is_null($videoData['status'])) $video->setStatus($videoData['status']);
			if (!is_null($videoData['published_at'])) $video->setPublishedAt($videoData['published_at']);

			$result = $video->update();

			if (!$result) {
				// $messages = $video->getMessages();

			 //    foreach ($messages as $message) {
			 //        echo $message;
			 //    }
				throw new ServiceException('Unable to update video', self::ERROR_UNABLE_UPDATE_VIDEO);
			} else {
				return self::getVideoById($_id);
			}

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * Deleting exicting video
	 *
	 * @param string $_id
	 */
	public function deleteVideoForever($_id)
	{
		try {
			$video = Videos::findFirst(
				[
					'conditions' => '_id = :_id:',
					'bind'       => [
						'_id' => $_id
					],
				]
			);

			if (!$video) {
				throw new ServiceException("Video not found", self::ERROR_VIDEO_NOT_FOUND);
			}

			$result = $video->delete();

			if (!$result) {
				throw new ServiceException('Unable to delete video', self::ERROR_UNABLE_DELETE_VIDEO);
			} else {
				return array("code"=>200, "status"=>"Video is deleted forever");
			}

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	public function deleteVideo($_id)
	{
		try {
			$video = Videos::findFirst(
				[
					'conditions' => '_id = :_id:',
					'bind'       => [
						'_id' => $_id
					],
				]
			);

			if (!$video) {
				throw new ServiceException('Video not found', self::ERROR_VIDEO_NOT_FOUND);
			}

			$result = $video->setStatus(self::STATUS_DEACTIVED_VIDEO)
				->update();

			if (!$result) {
				throw new ServiceException('Unable to delete video', self::ERROR_UNABLE_DELETE_VIDEO);
			} else {
				return self::getVideoList();
			}

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * Getting video by _id
	 *
	 * @param string $_id
	 */
	public function getVideoById($_id, array $searchParam = null)
	{
		try {
			$parameters = [];

			$conditions = $bind = [];

			$conditions[] = "_id = :_id:";
			$bind['_id'] = $_id;

			if (!isset($searchParam['_all_status'])){
				$conditions[] = "status = :status:";
				$bind['status'] = self::STATUS_NORMAL_VIDEO;
			}

			$parameters['conditions'] = implode(" AND ", $conditions);
			$parameters['bind'] = $bind;
			$parameters['columns'] = (is_null($searchParam['_fetch_all'])) ? self::FETCH_NOT_ALL_FIELDS : self::FETCH_ALL_FIELDS;

			$video = Videos::findFirst($parameters);

			if (!$video) {
				return [];
			}

			return [$video];

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}

	}

	public function getVideoByKeyword($keyword, array $searchParam = null)
	{
		try {
			$parameters = [];

			$conditions = $bind = [];

			$conditions[] = "keyword IN ('".implode("','", explode(",", $keyword))."')";
			$bind['keyword'] = "(" . $keyword  . ")";

			if (!isset($searchParam['_all_status'])){
				$conditions[] = "status = :status:";
				$bind['status'] = self::STATUS_NORMAL_VIDEO;
			}

			$parameters['conditions'] = implode(" AND ", $conditions);
			$parameters['bind'] = $bind;
			$parameters['columns'] = (is_null($searchParam['_fetch_all'])) ? self::FETCH_NOT_ALL_FIELDS : self::FETCH_ALL_FIELDS;

			$videos = Videos::find($parameters);

			if (!$videos) {
				return [];
			}

			return $videos->toArray();

		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * Getting video by param
	 *
	 * @param array $searchParam
	 *
	 * Param name 				: search by contain
	 * Param source_video 		: search by equal
	 * Param category 			: search by equal
	 * Param tag 				: search by contain
	 * Param description 		: search by contain
	 * Param source_video_id	: search by equal
	 * Param status 			: search by equal
	 * Param keyword 			: search by contain
	 * Param _all_status 		: ignore status
	 * Param _page 				: select by page number
	 * Param _size				: select by count
	 * Param _fetch_all 		: get all include _id, _created_at, _updated_at
	 *
	 */
	public function getVideoList(array $searchParam)
	{
		try {
			$parameters = [];

			$conditions = $bind = [];

			if (!(is_null($searchParam['name']))){
				$conditions[] = "name LIKE :name:";
				$bind['name'] = '%' . $searchParam['name'] . '%';
			}

			if (!(is_null($searchParam['source_video']))){
				$conditions[] = "source_video = :source_video:";
				$bind['source_video'] = $searchParam['source_video'];
			}

			if (!(is_null($searchParam['category']))){
				$conditions[] = "category = :category:";
				$bind['category'] = $searchParam['category'];
			}

			if (!(is_null($searchParam['tag']))){
				$conditions[] = "tag LIKE :tag:";
				$bind['tag'] = '%' . $searchParam['tag'] . '%';
			}

			if (!(is_null($searchParam['description']))){
				$conditions[] = "description LIKE :description:";
				$bind['description'] = '%' . $searchParam['description'] . '%';
			}

			if (!(is_null($searchParam['source_video_id']))){
				$conditions[] = "source_video_id = :source_video_id:";
				$bind['source_video_id'] = $searchParam['source_video_id'];
			}

			if (!(is_null($searchParam['source_full_url']))){
				$conditions[] = "source_full_url = :source_full_url:";
				$bind['source_full_url'] = $searchParam['source_full_url'];
			}

			if (!(is_null($searchParam['keyword']))){
				$conditions[] = "keyword IN ('".implode("','", explode(",", $searchParam['keyword']))."')";
				// $bind['keyword'] = implode("','", explode(",", $searchParam['keyword']));
			}

			if (!(is_null($searchParam['lengthgreaterthan']))){
				$conditions[] = "length > :lengthgreaterthan:";
				$bind['lengthgreaterthan'] = $searchParam['lengthgreaterthan'];
			}

			if (!(is_null($searchParam['lengthlessthan']))){
				$conditions[] = "length < :lengthlessthan:";
				$bind['lengthlessthan'] = $searchParam['lengthlessthan'];
			}

			if (is_null($searchParam['_all_status'])){
				if (!(is_null($searchParam['status']))){
					$conditions[] = "status = :status:";
					$bind['status'] = $searchParam['status'];
				} else {
					$conditions[] = "status = :status:";
					$bind['status'] = self::STATUS_NORMAL_VIDEO;
				}
			}

			$parameters['conditions'] = implode(" AND ", $conditions);
			$parameters['bind'] = $bind;

			$parameters['columns'] = (is_null($searchParam['_fetch_all'])) ? self::FETCH_NOT_ALL_FIELDS : self::FETCH_ALL_FIELDS;

			$parameters['order'] = 'published_at DESC';

			$_size = 10;
			if (!(is_null($searchParam['_size']))){
				$_size = ((int)$searchParam['_size'] > 50) ? 50 : (int)$searchParam['_size'];
			}
			$parameters['limit'] = $_size;

			if (!(is_null($searchParam['_page'])) && (int)$searchParam['_page'] > 0){
				$_offset = $_size * ((int)$searchParam['_page'] - 1);
				$parameters['offset'] = $_offset;
			}
			$videos = Videos::find($parameters);

			if (!$videos) {
				return [];
			}

			return $videos->toArray();
		} catch (\PDOException $e) {
			throw new ServiceException($e->getMessage(), $e->getCode(), $e);
		}
	}
}