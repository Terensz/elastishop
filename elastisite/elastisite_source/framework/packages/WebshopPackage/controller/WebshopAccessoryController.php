<?php
namespace framework\packages\WebshopPackage\controller;

use framework\component\parent\AccessoryController;
use framework\packages\ArticlePackage\entity\Article;
use framework\packages\ToolPackage\service\ImageService;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductImageRepository;

class WebshopAccessoryController extends AccessoryController
{
    public function getImagePath()
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
        $webshopService = $this->getContainer()->getService('WebshopService');
        return $webshopService->getImagePath();
    }

    /**
    * Route: [name: webshop_image_thumbnail, paramChain: /webshop/image/thumbnail/{slug}]
    */
    public function webshopImageThumbnailAction($slug)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        $repo = new ProductImageRepository();
        $productImage = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]]);
        if (!$productImage) {
            return false;
        }
        // dump($productImage);exit;
        $pathToFile = $this->getImagePath().'/thumbnail_'.$productImage->getImageCode().'.'.$productImage->getExtension();
        // dump($pathToFile);exit;
        return $imageService->loadImage($pathToFile, null, null);
    }

    /**
    * Route: [name: webshop_image_big, paramChain: /webshop/image/big/{slug}]
    */
    public function webshopImageBigAction($slug)
    {
        $this->getContainer()->wireService('ToolPackage/service/ImageService');
        $imageService = new ImageService();
        $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        $repo = new ProductImageRepository();
        $productImage = $repo->findOneBy(['conditions' => [['key' => 'slug', 'value' => $slug]]]);

        // dump($productImage);exit;
        if (!$productImage) {
            return false;
        }
        // dump($productImage);exit;
        $pathToFile = $this->getImagePath().'/image_'.$productImage->getImageCode().'.'.$productImage->getExtension();
        // dump($pathToFile);exit;
        return $imageService->loadImage($pathToFile, null, null);
    }
}
