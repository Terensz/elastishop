<?php
namespace framework\packages\VideoPackage\entity;

use framework\component\parent\DbEntity;
use framework\packages\VideoPackage\entity\Video;

class SelectedVideo extends DbEntity
{
    const STATUS_CODE_CONVERSIONS = [
        '0' => 'disabled',
        '1' => 'active'
    ];

    const CREATE_TABLE_STATEMENT = "CREATE TABLE `selected_video` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `video_id` int(20) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=36000 DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci";

    protected $id;
    protected $video;

    public function __construct()
    {
        $this->wireService('framework/packages/VideoPackage/entity/Video');
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVideo(Video $video = null)
    {
        $this->video = $video;
    }

    public function getVideo()
    {
        return $this->video;
    }
}
