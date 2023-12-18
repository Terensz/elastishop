<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class ImageController extends PageController
{
    /**
    * Route: [name: admin_uploads_images, paramChain: /admin/uploads/images]
    */
    public function adminUploadsImagesAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
