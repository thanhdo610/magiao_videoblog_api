<?php
namespace App\Models;

class Videos extends \Phalcon\Mvc\Model
{
    public $db;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $source_video;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $category;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $tag;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $description;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $source_video_id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $source_full_url;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $status;

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=255, nullable=false)
     */
    protected $_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $_created_at;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $_updated_at;

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field source_video
     *
     * @param string $source_video
     * @return $this
     */
    public function setSourceVideo($source_video)
    {
        $this->source_video = $source_video;

        return $this;
    }

    /**
     * Method to set the value of field category
     *
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Method to set the value of field tag
     *
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field source_video_id
     *
     * @param string $source_video_id
     * @return $this
     */
    public function setSourceVideoId($source_video_id)
    {
        $this->source_video_id = $source_video_id;

        return $this;
    }

    /**
     * Method to set the value of field source_full_url
     *
     * @param string $source_full_url
     * @return $this
     */
    public function setSourceFullUrl($source_full_url)
    {
        $this->source_full_url = $source_full_url;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field _id
     *
     * @param string $_id
     * @return $this
     */
    public function setId($_id)
    {
        $this->_id = $_id;

        return $this;
    }

    /**
     * Method to set the value of field _created_at
     *
     * @param string $_created_at
     * @return $this
     */
    public function setCreatedAt($_created_at)
    {
        $this->_created_at = $_created_at;

        return $this;
    }

    /**
     * Method to set the value of field _updated_at
     *
     * @param string $_updated_at
     * @return $this
     */
    public function setUpdatedAt($_updated_at)
    {
        $this->_updated_at = $_updated_at;

        return $this;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field source_video
     *
     * @return string
     */
    public function getSourceVideo()
    {
        return $this->source_video;
    }

    /**
     * Returns the value of field category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns the value of field tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field source_video_id
     *
     * @return string
     */
    public function getSourceVideoId()
    {
        return $this->source_video_id;
    }

    /**
     * Returns the value of field source_full_url
     *
     * @return string
     */
    public function getSourceFullUrl()
    {
        return $this->source_full_url;
    }

    /**
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field _id
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Returns the value of field _created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_created_at;
    }

    /**
     * Returns the value of field _updated_at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_updated_at;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->db = $this->getDi()->getShared('db');
        $this->setSchema("blogging");
        $this->setSource("videos");
        $this->skipAttributes(
            [
                '_created_at',
                '_updated_at',
            ]
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'videos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Videos[]|Videos|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Videos|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
