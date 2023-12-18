<?php
namespace framework\packages\AppearancePackage\controller;

use App;
use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\kernel\utility\FileHandler;
use framework\component\parent\ImageResponse;
use framework\packages\ToolPackage\service\ImageUploader;
use framework\packages\ToolPackage\service\ImageService;

class FaviconWidgetController extends WidgetController
{
    public function getFilePath($relative = false)
    {
        $filePath = 'projects/'.App::getWebProject().'/favicon';
        return $relative ? $filePath : FileHandler::completePath($filePath, 'dynamic');
    }
    /**
    * name: admin_favicon_widget, paramChain: /admin/favicon/widget
    */
    public function adminFaviconWidgetAction()
    {
        $fileNames = FileHandler::getAllFileNames($this->getFilePath(), 'keep');
        $currentFaviconName = false;
        $width = $height = null;
        foreach ($fileNames as $faviconName) {
            $currentFaviconName = $faviconName;
            $size = getimagesize($this->getFilePath().'/'.$currentFaviconName);
            $width = $size[0];
            $height = $size[1];
        }

        // dump($currentFaviconName);exit;

        $viewPath = 'framework/packages/AppearancePackage/view/widget/AdminFaviconWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminFaviconWidget', $viewPath, [
                'container' => $this->getContainer(),
                'currentFaviconName' => $currentFaviconName,
                'width' => $width,
                'height' => $height
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_favicon_cropModal, paramChain: /admin/favicon/cropModal
    */
    public function adminFaviconCropModalAction()
    {
        $viewPath = 'framework/packages/AppearancePackage/view/widget/AdminFaviconWidget/cropModal.php';
        $response = [
            'view' => $this->renderWidget('AdminBackgroundsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_favicon_uploadCroppedCanvas, paramChain: /admin/favicon/uploadCroppedCanvas
    */
    public function adminFaviconUploadCroppedCanvasAction()
    {
        // $faviconRelPath = 'public_folder/favicon';
        // $upload = $this->getContainer()->getKernelObject('UploadRequest')->get(0);
        $rawCanvas = $this->getContainer()->getRequest()->get('canvas');
        $image = str_replace('data:image/png;base64,', '', $rawCanvas);
        $image = str_replace(' ', '+', $image);
        $image = base64_decode($image);
        $fileNames = FileHandler::getAllFileNames($this->getFilePath(), 'keep');
        foreach ($fileNames as $faviconName) {
            $ext = BasicUtils::explodeAndGetElement($faviconName, '.', 'last');
            file_put_contents($this->getFilePath().'/favicon.'.$ext, $image);
        }

        $response = [
            'view' => '',
            'data' => [
                'faviconPath' => $this->getFilePath(true).'/favicon.'.$ext
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * name: admin_favicon_uploadModal, paramChain: /admin/favicon/uploadModal
    */
    public function adminFaviconUploadModalAction()
    {
        $viewPath = 'framework/packages/AppearancePackage/view/widget/AdminFaviconWidget/uploadModal.php';
        $response = [
            'view' => $this->renderWidget('AdminBackgroundsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * name: admin_favicon_upload, paramChain: /admin/favicon/upload
    */
    public function adminFaviconUploadAction()
    {
        $this->wireService('ToolPackage/service/ImageUploader');
        $upload = $this->getContainer()->getKernelObject('UploadRequest')->get(0);
        $fileNames = FileHandler::getAllFileNames($this->getFilePath(), 'keep');
        // dump($fileNames);exit;
        foreach ($fileNames as $faviconName) {
            FileHandler::unlinkFile($this->getFilePath().'/'.$faviconName);
        }

        $ext = BasicUtils::explodeAndGetElement($upload->getName(), '.', 'last');
        $uploader = new ImageUploader();
        $uploader->setImgurFormat(false);
        $uploader->setFilePath($this->getFilePath());
        $uploader->setFileName('favicon');
        $uploadResult = $uploader->upload();
        if ($uploadResult['success']) {

        }

        $response = [
            'view' => '',
            'data' => [
                'faviconPath' => $this->getFilePath(true).'/favicon.'.$ext
            ]
        ];

        return new JsonResponse($response);
    }

    /**
    * name: admin_skins_widget, paramChain: /admin/skins/widget
    */
    public function adminSkinsWidgetAction()
    {
        $viewPath = 'framework/packages/AppearancePackage/view/widget/AdminSkinsWidget/widget.php';
        $response = [
            'view' => $this->renderWidget('AdminSkinsWidget', $viewPath, [
                'container' => $this->getContainer()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }
}
