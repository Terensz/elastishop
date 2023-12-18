<?php
namespace framework\packages\SiteBuilderPackage\controller;

use App;
use framework\component\parent\WidgetController;

class SplashWidgetController extends WidgetController
{
    public $contentEditorWidgetController;
    
    public function getContentEditorWidgetController()
    {
        if (!$this->contentEditorWidgetController) {
            App::getContainer()->wireService('SiteBuilderPackage/controller/ContentEditorWidgetController');
            $this->contentEditorWidgetController = new ContentEditorWidgetController();
        }

        return $this->contentEditorWidgetController;
    }

    public function splashWidgetAction()
    {
        return $this->getContentEditorWidgetController()->contentEditorWidgetAction();
    }

    public function wrappedSplashWidgetAction()
    {
        return $this->getContentEditorWidgetController()->wrappedContentEditorWidgetAction();
    }
}
