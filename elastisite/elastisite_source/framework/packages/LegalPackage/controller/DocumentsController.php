<?php
namespace framework\packages\LegalPackage\controller;

use framework\component\parent\PageController;

class DocumentsController extends PageController
{
    /**
    * Route: [name: document_reader, paramChain: /documents/{slug}]
    */
    public function documentReaderAction($slug)
    {
        return $this->renderPage([
            'container' => $this->getContainer()
        ]);
    }
}
