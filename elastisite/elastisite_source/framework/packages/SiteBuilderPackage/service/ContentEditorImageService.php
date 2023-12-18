<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\parent\Service;
use framework\kernel\utility\FileHandler;
use framework\packages\SiteBuilderPackage\entity\ContentEditor;

class ContentEditorImageService extends Service
{
    public static function getContentEditorAbsoluteImageDir($webProject = null)
    {
        $relativeImageDir = self::getContentEditorRelativeImageDir($webProject);
        $filePath = FileHandler::completePath($relativeImageDir, 'dynamic');

        return $filePath;
    }

    public static function getContentEditorRelativeImageDir($webProject = null)
    {
        $filePath = 'projects/'.($webProject ? $webProject : App::getWebProject()).'/upload/contentEditor/contentEditorBackgroundImage';

        return $filePath;
    }

    public static function createBackgroundImageLink(ContentEditor $contentEditor)
    {
        return '/contentEditor/showBackgroundImage/'.$contentEditor->getId();
    }

    public static function removeBackgroundImage(ContentEditor $contentEditor)
    {
        // dump($contentEditor->getContentEditorBackgroundImage()->getImageHeader());exit;
        if (!$contentEditor->getContentEditorBackgroundImage()) {
            return false;
        }
        foreach ($contentEditor->getContentEditorBackgroundImage()->getImageHeader()->getImageFile() as $imageFile) {
            $file = $imageFile->getFile();
            $pathToFile = FileHandler::completePath(ltrim($file->getPath().'/'.$file->getFileName().'.'.$file->getExtension(), '/'), 'dynamic');
            $removed = @unlink($pathToFile);
            $file->getRepository()->remove($file->getId());
            $imageFile->getRepository()->remove($imageFile->getId());
            // dump($removed);
            // dump($imageFile);
        }
        $contentEditor->getContentEditorBackgroundImage()->getImageHeader()->getRepository()->remove($contentEditor->getContentEditorBackgroundImage()->getImageHeader()->getId());
        $contentEditor->getContentEditorBackgroundImage()->getRepository()->remove($contentEditor->getContentEditorBackgroundImage()->getId());
        $contentEditor->getContentEditorBackgroundImage(null);

        return $contentEditor;
        // unlink(self::getContentEditorAbsoluteImageDir().'/')
    }
}
