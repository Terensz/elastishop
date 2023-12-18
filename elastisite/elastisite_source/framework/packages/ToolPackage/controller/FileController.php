<?php
namespace framework\packages\ToolPackage\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class FileController extends PageController
{
    /**
    * Route: [name: admin_uploads_attachments, paramChain: /admin/uploads/files]
    */
    public function adminUploadsFilesAction()
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
