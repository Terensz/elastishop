<?php
namespace framework\packages\SiteBuilderPackage\service;

use App;
use framework\component\parent\Service;
use framework\packages\SiteBuilderPackage\entity\ContentEditor;
use framework\packages\SiteBuilderPackage\repository\ContentEditorRepository;

class ContentEditorService extends Service
{
    public static $contentEditors;

    public static $contentEditorRepository;

    public static function getContentEditor() : ContentEditor
    {
        $routeName = App::getContainer()->getRouting()->getPageRoute()->getName();
        $contentEditorUniqueCode = $routeName;

        if (isset(self::$contentEditors[$contentEditorUniqueCode])) {
            return self::$contentEditors[$contentEditorUniqueCode];
        }

        $contentEditor = self::getContentEditorRepository()->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'route_name', 'value' => $routeName]
        ]]);

        if (!$contentEditor) {
            $contentEditor = new ContentEditor();
            $contentEditor->setRouteName($routeName);
            $contentEditor = self::getContentEditorRepository()->store($contentEditor);
        }
        self::$contentEditors[$contentEditorUniqueCode] = $contentEditor;

        return $contentEditor;
    }

    public static function getContentEditorRepository() : ContentEditorRepository
    {
        if (!self::$contentEditorRepository) {
            App::getContainer()->wireService('SiteBuilderPackage/repository/ContentEditorRepository');
            self::$contentEditorRepository = new ContentEditorRepository();
        }

        return self::$contentEditorRepository;
    }
}
