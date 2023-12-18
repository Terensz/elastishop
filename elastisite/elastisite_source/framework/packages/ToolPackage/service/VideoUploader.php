<?php
namespace framework\packages\ToolPackage\service;

use framework\component\parent\Service;
use framework\packages\ToolPackage\entity\TechnicalFile;
use framework\packages\ToolPackage\repository\TechnicalFileRepository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\request\Upload;
use framework\packages\VideoPackage\service\VideoService;

class VideoUploader extends Uploader
{
    // private $fileName;
    // private $filePath;
    protected $createThumb;
    protected $imgurFormat = false;

    public function __construct()
    {

    }

    public function setCreateThumb($createThumb)
    {
        $this->createThumb = $createThumb;
    }

    public function getCreateThumb()
    {
        return $this->createThumb;
    }

    public function setImgurFormat($imgurFormat)
    {
        $this->imgurFormat = $imgurFormat;
    }

    // public function autoCreateFileName($uploadedName)
    // {
    //     return $this->getSession()->get('visitorCode').'_'.BasicUtils::slugify(
    //         BasicUtils::explodeAndRemoveElement($uploadedName, '.', 'last')
    //     );
    // }

    private function handleUpload($params, $upload)
    {
        $errorMessage = null;
        $success = false;
        $uploadSuccess = false;
        $file = new TechnicalFile();
        $this->wireService('VideoPackage/service/VideoService');

        if (!in_array($params['mime'], VideoService::SUPPORTED_UPLOAD_MIMES)) {
            $errorMessage = 'not.video.file';
        }
        // dump($params);exit;
        if (!$errorMessage) {
            $file->setTitle($params['title']);
            $file->setName($params['name']);
            $file->setMime($params['mime']);
            $file->setOriginalName(isset($params['originalName']) ? $params['originalName'] : $params['name']);
            $file->setPath(isset($params['filePath']) ? $params['filePath'] : $this->filePath);
            $file->setExtension($params['extension']);
            $pathToFile = $file->getPath().'/'.$file->getName().'.'.$upload->getExtension();
            $uploadSuccess = $upload->saveTo($pathToFile);
            // dump($uploadSuccess);
        }
        
        if ($uploadSuccess) {
            $success = true;
        }
        return array(
            'file' => $file,
            'success' => $success,
            'errorMessage' => trans($errorMessage)
        );
    }

    public function upload()
    {
        $this->getContainer()->wireService('ToolPackage/entity/TechnicalFile');
        $this->getContainer()->wireService('ToolPackage/repository/TechnicalFileRepository');
        $this->getContainer()->wireService('framework/kernel/request/Upload');

        $uploads = $this->getUploadRequest()->getAll();
        if (!isset($uploads) || !is_array($uploads)) {
            return false;
        }
        foreach ($uploads as $uploadLoop) {
            // dump($uploadLoop); exit;
            $upload = $uploadLoop;
        }

        $params = array(
            'title' => $upload->getName(),
            'name' => !$this->fileName ? $this->autoCreateFileName($upload->getName()) : $this->fileName,
            'mime' => $upload->getMime(),
            'extension' => $upload->getExtension()
        );

        $handledUpload = $this->handleUpload($params, $upload);
        // $this->success = $handledUpload['success'];

        $uploadResult = array(
            'success' => $handledUpload['success'],
            'errorMessage' => $handledUpload['errorMessage'],
            'data' => array(
                'fileName' => $handledUpload['file']->getName(),
                'extension' => $upload->getExtension(),
                // 'width' => $upload->getWidth(),
                // 'height' => $upload->getHeight(),
                'size' => $upload->getSize(),
                'mime' => $upload->getMime()
            )
        );

        // dump($uploadResult);exit;

        return $uploadResult;
    }
}
