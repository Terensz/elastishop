<?php
namespace framework\packages\BackgroundPackage\controller;

use framework\kernel\utility\BasicUtils;
use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;
use framework\kernel\utility\FileHandler;

class BackgroundController extends PageController
{
    /**
    * Route: [name: admin_background_bindings, paramChain: /admin/background/bindings]
    */
    public function adminBackgroundBindingsAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_backgrounds, paramChain: /admin/backgrounds]
    */
    public function adminBackgroundsAction()
    {
        $rawBgTempDir = 'temp/rawBgImage';

        $files = FileHandler::getAllFileNames($rawBgTempDir, 'keep', 'dynamic');
        foreach ($files as $fileName) {
            FileHandler::unlinkFile($rawBgTempDir.'/'.$fileName, 'dynamic');
        }

        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
