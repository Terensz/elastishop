<?php
namespace framework\packages\WebshopPackage\controller;

use framework\component\parent\WidgetController;
use framework\component\parent\JsonResponse;
use framework\kernel\utility\BasicUtils;
use framework\component\parent\ImageResponse;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductImageRepository;
use framework\packages\ToolPackage\service\ImageUploader;
use framework\packages\ToolPackage\service\ImageService;
// use framework\packages\WebshopPackage\repository\ProductImageActiveRepository;
use framework\packages\ToolPackage\service\Grid\GridFactory;
use framework\packages\FrameworkPackage\service\GridAjaxInterface;
use framework\packages\FormPackage\service\FormBuilder;
use framework\packages\UserPackage\entity\UserAccount;
use framework\packages\UserPackage\entity\Person;

class ProductImageWidgetController extends WidgetController
{
    public function getImagePath($relative = false)
    {
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
        $webshopService = $this->getContainer()->getService('WebshopService');

        return $webshopService->getImagePath($relative);
    }

    /**
    * Route: [name: admin_webshop_productImage_list, paramChain: /admin/webshop/productImage/list]
    */
    public function adminProductImageListAction()
    {
        $productId = (int)$this->getContainer()->getRequest()->get('productId');
        // dump($productId);exit;
        $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        $productImageRepo = new ProductImageRepository();

        // $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        // $productRepo = new ProductRepository();

        // dump($productRepo->find(4));exit;
        // dump($productImageRepo->findBy(['product_id' => $productId]));exit;

        // $this->getContainer()->wireService('WebshopPackage/repository/ProductImageActiveRepository');
        // $productImageActiveRepo = new ProductImageActiveRepository();

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/productImageList.php';
        $response = [
            'view' => $this->renderWidget('editProduct', $viewPath, [
                'container' => $this->getContainer(),
                'productId' => $productId,
                'productImages' => $productImageRepo->findBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]),
                'productImageRepo' => $productImageRepo
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productImage_new, paramChain: /admin/webshop/productImage/new]
    */
    public function adminProductImageNewAction()
    {
        $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        $productImageRepo = new ProductImageRepository();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        $productRepo = new ProductRepository();

        $productId = (int)$this->getContainer()->getRequest()->get('productId');

        $viewPath = 'framework/packages/WebshopPackage/view/widget/AdminWebshopProductsWidget/newProductImage.php';
        $response = [
            'view' => $this->renderWidget('newProductImage', $viewPath, [
                'container' => $this->getContainer(),
                // 'form' => $form,
                'productId' => $productId
                // 'productCategories' => $productCategoryRepo->findAll()
            ]),
            'data' => []
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productImage_setAsMain, paramChain: /admin/webshop/productImage/setAsMain]
    */
    public function adminProductImageSetAsMainAction()
    {
        // $this->getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        // $productRepo = new ProductRepository();

        $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        $productImageRepo = new ProductImageRepository();

        $productId = (int)$this->getContainer()->getRequest()->get('productId');
        $productImageId = (int)$this->getContainer()->getRequest()->get('productImageId');

        $productImages = $productImageRepo->findBy(['conditions' => [['key' => 'product_id', 'value' => $productId]]]);
        
        foreach ($productImages as $productImage) {
            if ($productImage->getId() == $productImageId) {
                $productImage->setMain(1);
            } else {
                $productImage->setMain(0);
            }
            // $productImage->setMain(1);
            $productImageRepo->store($productImage);
        }

        $response = [
            'view' => ''
        ];

        exit;

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productImage_delete, paramChain: /admin/webshop/productImage/delete]
    */
    public function adminProductImageDeleteAction()
    {
        // dump($this->getContainer()->getRequest()->getAll());//exit;
        $this->getContainer()->wireService('WebshopPackage/repository/ProductImageRepository');
        $repo = new ProductImageRepository();
        $id = (int)$this->getContainer()->getRequest()->get('id');
        $productImage = $repo->find($id);
        $pathToThumbnail = $this->getImagePath().'/thumbnail_'.$productImage->getImageCode().'.'.$productImage->getExtension();
        if (is_file($pathToThumbnail)) {
            unlink($pathToThumbnail);
        }
        $pathToImage = $this->getImagePath().'/image_'.$productImage->getImageCode().'.'.$productImage->getExtension();
        if (is_file($pathToImage)) {
            unlink($pathToImage);
        }
        $repo->remove($id);
        // dump($repo); exit;

        $response = [
            'view' => ''
        ];

        return $this->widgetResponse($response);
    }

    /**
    * Route: [name: admin_webshop_productImage_upload, paramChain: /admin/webshop/productImage/upload]
    */
    public function adminProductImageUploadAction($productId)
    {
        $success = false;
        $this->wireService('ToolPackage/service/ImageUploader');
        $upload = $this->getContainer()->getKernelObject('UploadRequest')->get(0);
        $this->wireService('WebshopPackage/repository/ProductRepository');
        $productRepo = new ProductRepository();
        $product = $productRepo->find($productId);
        if ($product) {
            $imageCode = $productId.'_'.time();
            // $ext = BasicUtils::explodeAndGetElement($upload->getName(), '.', 'last');
            $uploader = new ImageUploader();
            // $uploader->setIsImage(true);
            $uploader->setImgurFormat(false);
            $uploader->setFilePath($this->getImagePath());
            $uploader->setFileName('image_'.$imageCode);
            // $ext = BasicUtils::explodeAndGetElement($uploader->getFileName(), '.', 'last');
            $uploadResult = $uploader->upload();
            // dump($uploadResult);exit;
            if ($uploadResult['success']) {
                $this->getContainer()->wireService('ToolPackage/service/ImageService');
                $imageService = new ImageService();
                // $imageService->createThumbnail($this->getImagePath(true).'/image_'.$imageCode.'.'.$uploadResult['data']['extension'], 'thumbnail_'.$imageCode, null, ImageService::$thumbnailSizes[ImageService::IMAGE_TYPE_THUMBNAIL_H400]['height']);
                $imageService->createThumbnail($this->getImagePath(true).'/image_'.$imageCode.'.'.$uploadResult['data']['extension'], 'thumbnail_'.$imageCode, ImageService::$thumbnailSizes[ImageService::IMAGE_TYPE_THUMBNAIL_W550]['width'], null);

                $this->wireService('WebshopPackage/repository/ProductImageRepository');
                $productImageRepo = new ProductImageRepository();
                $productImage = $productImageRepo->createNewEntity();
                $productImage->setProduct($product);
                $productImage->setSlug($productImageRepo->generateSlug());
                $productImage->setImageCode($imageCode);
                $productImage->setExtension($uploadResult['data']['extension']);
                $productImage->setMime($uploadResult['data']['mime']);
                $productImage->setMain(0);
                $productImage->setStatus(1);
                $productImageRepo->store($productImage);
                $success = true;
                // dump($productImage);exit;
            }
        }

        $response = [
            'view' => '',
            'data' => [
                'success' => $success
            ]
        ];

        return new JsonResponse($response);
    }
}
