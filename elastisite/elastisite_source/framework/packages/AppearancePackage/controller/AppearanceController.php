<?php
namespace framework\packages\AppearancePackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class AppearanceController extends PageController
{
    /**
    * Route: [name: admin_favicon, paramChain: /admin/favicon]
    */
    public function adminFaviconAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: admin_appearance_skins, paramChain: /admin/appearance/skins]
    */
    public function adminAppearanceSkinsAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
