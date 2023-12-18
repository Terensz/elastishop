<?php
namespace projects\ASC\controller;

use framework\component\parent\PageController;
use framework\packages\ArticlePackage\entity\Article;

class ElastiSiteController extends PageController
{
    /**
    * Route: [name: elastisite_index, paramChain: /elastisite]
    */
    public function indexAction()
    {
        // $countryClassification = $this->getCountryClassification();
        // dump($countryClassification);exit;
        
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }

    /**
    * Route: [name: elastisite_logo_page, paramChain: /elastisite/logo/{logoId}]
    */
    public function elastiSiteLogoPageAction()
    {
        // $countryClassification = $this->getCountryClassification();
        // dump($countryClassification);exit;
        
        return $this->renderPage([
            'container' => $this->getContainer(),
            'skinName' => 'Basic'
        ]);
    }
}
