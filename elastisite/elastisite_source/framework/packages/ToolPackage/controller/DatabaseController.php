<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class DatabaseController extends PageController
{
    /**
    * Route: [name: admin_database_info, paramChain: /admin/database/info]
    */
    public function adminDatabaseInfoAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
