<?php
namespace framework\packages\ToolPackage\service;

use framework\component\parent\Service;
use framework\packages\ToolPackage\entity\TechnicalFile;
use framework\packages\ToolPackage\repository\FileRepository;
use framework\kernel\utility\BasicUtils;
use framework\kernel\request\Upload;

class ImageUploader extends Uploader
{
    // private $fileName;
    // private $filePath;
    protected $createThumb;
    protected $imgurFormat = false;

    public function __construct()
    {

    }

    public function getImageService() : ImageService
    {
        $this->setService('ToolPackage/service/ImageService');

        return $this->getService('ImageService');
    }

    // public function setFileName($fileName)
    // {
    //     $this->fileName = $fileName;
    // }

    // public function getFileName()
    // {
    //     return $this->fileName;
    // }

    // public function setFilePath($filePath)
    // {
    //     $this->filePath = $filePath;
    // }

    // public function getFilePath()
    // {
    //     return $this->filePath;
    // }

    // public function setIsImage($isImage)
    // {
    //     $this->isImage = $isImage;
    // }

    // public function getIsImage()
    // {
    //     return $this->isImage;
    // }

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

        if (!in_array($params['mime'], ($this->getImageService())::SUPPORTED_UPLOAD_MIMES)) {
            $errorMessage = 'not.image.file';
        }

        // dump($params);exit;
        if (!$errorMessage) {
            $file->setTitle($params['title']);
            $file->setName($params['fileName']);
            $file->setMime($params['mime']);
            $file->setOriginalName(isset($params['originalName']) ? $params['originalName'] : $params['fileName']);
            $file->setPath(isset($params['filePath']) ? $params['filePath'] : $this->filePath);

            $extension = $this->getImageService()->determineExtension($params['extension']);
            $file->setExtension($extension);
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
            'errorMessage' => $errorMessage
        );
    }

    public function upload()
    {
        $this->getContainer()->wireService('ToolPackage/entity/TechnicalFile');
        $this->getContainer()->wireService('ToolPackage/repository/FileRepository');
        $this->getContainer()->wireService('framework/kernel/request/Upload');

        $uploadRequests = $this->getUploadRequest()->getAll();
        if (!isset($uploadRequests) || !is_array($uploadRequests)) {
            return false;
        }
        foreach ($uploadRequests as $uploadRequestLoop) {
            $uploadRequest = $uploadRequestLoop;
        }

        if ($this->getFileName() && $this->getExtension()) {
            $fileName = $this->getFileName();
            $extension = $this->getExtension();
        } else {
            // dump($uploadRequest);
            $fileName = $this->getFileName() ? : $this->autoCreateFileName($uploadRequest->getName());
            $extension = $uploadRequest->getExtension();
            $this->extension = $extension;
            // dump($extension);
        }
        $mime = $uploadRequest->getMime();

        $params = array(
            'title' => $this->getTitle() ? : $uploadRequest->getName(),
            'fileName' => $fileName,
            'mime' => $mime,
            'extension' => $extension
        );

        $handledUpload = $this->handleUpload($params, $uploadRequest);

        $uploadResult = array(
            'success' => $handledUpload['success'],
            'errorMessage' => $handledUpload['errorMessage'],
            'data' => array(
                'fileName' => $fileName,
                'extension' => $extension,
                'width' => $uploadRequest->getWidth(),
                'height' => $uploadRequest->getHeight(),
                'size' => $uploadRequest->getSize(),
                'mime' => $mime
            )
        );

        // dump($uploadResult);exit;

        return $this->imgurFormat ? $this->getImgurFormat($uploadResult) : $uploadResult;
    }

    public function getImgurFormat($uploadResult = null)
    {
        if (!$uploadResult) {
            return null;
        }
        $now = $this->getCurrentTimestamp();
        $now->format('U');

        return array(
            'data' => array (
                'account_id' => 0,
                'account_url' => null,
                'ad_type' => 0,
                'ad_url' => "",
                'animated' => false,
                'bandwidth' => 0,
                'datetime' => $now,
                'deletehash' => null,
                'description' => null,
                'edited' => "0",
                'favorite' => false,
                'has_sound' => false,
                'width' => $uploadResult['data']['width'],
                'height' => $uploadResult['data']['height'],
                'id' => $uploadResult['data']['fileName'],
                'in_gallery' => false,
                'in_most_viral' => false,
                'is_ad' => false,
                'link' => $this->getContainer()->getUrl()->getHttpDomain().'/image/'.$uploadResult['data']['fileName'].'.'.$uploadResult['data']['extension'],
                'name' => "",
                'nsfw' => null,
                'section' => null,
                'size' => $uploadResult['data']['size'],
                'tags' => [],
                'title' => null,
                'type' => $uploadResult['data']['mime'],
                'views' => 0,
                'vote' => null
            ),
            'status' => 200,
            'success' => true
        );
    }
}
