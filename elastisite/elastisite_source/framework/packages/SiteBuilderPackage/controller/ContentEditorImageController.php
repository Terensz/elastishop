<?php
namespace framework\packages\SiteBuilderPackage\controller;

use framework\component\parent\AccessoryController;
use framework\packages\SiteBuilderPackage\repository\ContentEditorRepository;
use framework\packages\ToolPackage\service\ImageService;
use framework\packages\SiteBuilderPackage\service\ContentEditorImageService;

class ContentEditorImageController extends AccessoryController
{
    /**
    * Route: [name: contentEditor_showBackgroundImage, paramChain: /contentEditor/showBackgroundImage/{contentEditorId}]
    */
    public function showContentEditorBackgroundImageAction($contentEditorId)
    {
        // dump($contentEditorId);exit;
        $this->getContainer()->wireService('SiteBuilderPackage/service/ContentEditorImageService');
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $this->getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
        $imageService = new ImageService();
        $contentEditorRepository = new ContentEditorRepository();
        $contentEditor = $contentEditorRepository->find($contentEditorId);

        if (!$contentEditor) {
            return '';
        }
        $pathToFile = $contentEditor->getPathToBackgroundImage();
        // dump($pathToFile);exit;

        return $imageService->loadPathToImage($pathToFile);
    }
}
