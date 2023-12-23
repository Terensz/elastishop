<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductVisitHistory;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductVisitHistoryRepository;

class ProductVisitHistoryService extends Service
{
    public static function save(int $productId) : ProductVisitHistory
    {
        App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
        App::getContainer()->wireService('WebshopPackage/entity/Product');
        App::getContainer()->wireService('WebshopPackage/entity/ProductVisitHistory');
        App::getContainer()->wireService('WebshopPackage/repository/ProductVisitHistoryRepository');

        // $productIdPost = (int)App::getContainer()->getRequest()->get('productId');
        // if (empty($productIdPost)) {
        //     return false;
        // }

        $productVisitHistoryRepository = new ProductVisitHistoryRepository();
        $productRepository = new ProductRepository();

        $productVisitHistory = $productVisitHistoryRepository->findOneBy(['conditions' => [
            ['key' => 'website', 'value' => App::getWebsite()],
            ['key' => 'product_id', 'value' => $productId],
            ['key' => 'visitor_code', 'value' => App::getContainer()->getSession()->get('visitorCode')]
        ]]);

        if ($productVisitHistory) {
            $numberOfVisits = $productVisitHistory->getNumberOfVisits();
            $numberOfVisits++;
            $productVisitHistory->setNumberOfVisits($numberOfVisits);
        } else {
            $product = $productRepository->find($productId);
            if (!$product) {
                return false;
            }
            $productVisitHistory = new ProductVisitHistory();
            $productVisitHistory->setNumberOfVisits(1);
            $productVisitHistory->setProduct($product);
            $productVisitHistory->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
        }
        $productVisitHistory->setUpdatedAt(App::getContainer()->getCurrentTimestamp());
        $productVisitHistory = $productVisitHistoryRepository->store($productVisitHistory);

        return $productVisitHistory;
    }
}