<?php
namespace framework\packages\VideoPackage\service;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\packages\VideoPackage\repository\VideoRepository;

class VideoService extends DbRepository
{
    public const SUPPORTED_UPLOAD_MIMES = array(
        'video/x-flv', 'video/mp4', 'video/x-msvideo', 'video/x-ms-wmv', 'video/quicktime', 'video/3gpp'
    );

    public const SUPPORTED_EXTENSIONS = array(
        'flv', 'mp4', 'avi', 'wmv', 'mov', '3gp'
    );

    public function determineExtension($imageName)
    {
        $extension = BasicUtils::explodeAndGetElement($imageName, '.', 'last');
        $extension = strtolower($extension);
        return (in_array($extension, self::SUPPORTED_EXTENSIONS)) ? $extension : null;
    }
    
    public function cleanUpUnusedFiles($exceptCode, $exceptExtension)
    {
        // return true;
        $this->getContainer()->wireService('VideoPackage/repository/VideoRepository');
        $repo = new VideoRepository();

        $files = FileHandler::getAllFileNames($this->getFilePath());
        foreach ($files as $file) {
            $fileName = BasicUtils::explodeAndGetElement($file, '/', 'last');
            $code = BasicUtils::explodeAndRemoveElement($fileName, '.', 'last');
            $extension = BasicUtils::explodeAndGetElement($fileName, '.', 'last');
            $codeParts = explode('_', $code);
            if ($codeParts[0] == $this->getSession()->get('visitorCode') && ($code != $exceptCode || $extension != $exceptExtension)) {
                $video = $repo->findOneBy(['conditions' => [['key' => 'code', 'value' => $code], ['key' => 'extension', 'value' => $extension]]]);
                if (!$video) {
                    $pathToFile = $this->getFilePath().'/'.$fileName;
                    unlink($pathToFile);
                }
            }

            // $video = $repo->findOneBy(['conditions' => [['key' => 'code', 'value' => $code], ['key' => 'extension', 'value' => $extension]]]);
            // if (!$video) {
            //     $pathToFile = $this->getFilePath().'/'.$fileName;
            //     unlink($pathToFile);
            // }
        }
    }

    public function getFilePath()
    {
        $filePath = rtrim($this->getContainer()->getPathBase('dynamic'), '/').'/projects/'.App::getWebProject().'/upload/video';
        return $filePath;
    }
}
