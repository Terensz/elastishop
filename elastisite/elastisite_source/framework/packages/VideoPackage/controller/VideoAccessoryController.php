<?php
namespace framework\packages\VideoPackage\controller;

use framework\component\parent\AccessoryController;
use framework\packages\ToolPackage\service\ImageService;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\packages\VideoPackage\service\VideoStream;
use framework\kernel\utility\FileHandler;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class VideoAccessoryController extends AccessoryController
{
    public function getVideoService()
    {
        $this->setService('VideoPackage/service/VideoService');
        return $this->getService('VideoService');
    }

    public function getFilePath()
    {
        return $this->getVideoService()->getFilePath();
    }

    /**
    * Route: [name: videoPlayer_play, paramChain: /videoPlayer/play/{fileName}]
    */
    public function playVideoAction($fileName)
    {
        // dump($fileName);exit;
        $this->getContainer()->wireService('VideoPackage/service/VideoStream');
        // $videoRenderer = new VideoStream(FileHandler::completePath('video/demo/demo.mp4', 'dynamic'));

        $videoRenderer = new VideoStream($this->getFilePath().'/'.$fileName);
        return $videoRenderer->start();
        // $response = [
        //     'view' => $videoRenderer->start(),
        //     'data' => []
        // ];
    }

    /**
    * Route: [name: video_preview, paramChain: /video/preview/{fileName}]
    */
    public function videoPreviewWidgetAction($fileName)
    {
        // FileHandler::includeFileOnce('thirdparty/FFMpeg/FFMpeg.php', 'source');
        
        // $this->getVideoService()->wireFFMpeg();
        // $sec = 10;
        // $movie = $this->getFilePath().'/'.$fileName;
        // $thumbnail = 'thumbnail.png';

        // $ffmpeg = FFMpeg::create();
        // $video = $ffmpeg->open($movie);
        // $frame = $video->frame(TimeCode::fromSeconds($sec));
        // $frame->save($thumbnail);
        // dump($frame);exit;
        // echo '<img src="'.$thumbnail.'">';
    }
}
