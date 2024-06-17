<?php
namespace framework\packages\WebshopPackage\service;

use App;
use framework\component\helper\StringHelper;
use framework\component\parent\Service;
use framework\packages\WebshopPackage\dataProvider\ProductDataProvider;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductVisitHistory;
use framework\packages\WebshopPackage\repository\ProductRepository;
use framework\packages\WebshopPackage\repository\ProductVisitHistoryRepository;

class ProductVisitHistoryService extends Service
{
    const SEARCH_LIMIT = 10;

    public static function save(int $productId)
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

        $productVisitHistory = $productVisitHistoryRepository->findOneBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'product_id', 'value' => $productId],
                ['key' => 'visitor_code', 'value' => App::getContainer()->getSession()->get('visitorCode')]
            ]
        ]);

        if ($productVisitHistory) {
            $numberOfVisits = $productVisitHistory->getNumberOfVisits();
            $numberOfVisits++;
            $productVisitHistory->setNumberOfVisits($numberOfVisits);
            // dump($productVisitHistory);exit;
        } else {
            $product = $productRepository->find($productId);
            if (!$product || ($product && !empty($product->getSpecialPurpose()))) {
                // dump($product);exit;
                return false;
            }
            $productVisitHistory = new ProductVisitHistory();
            $productVisitHistory->setNumberOfVisits(1);
            $productVisitHistory->setProduct($product);
            $productVisitHistory->setVisitorCode(App::getContainer()->getSession()->get('visitorCode'));
        }
        $productVisitHistory->setUpdatedAt(App::getContainer()->getCurrentTimestamp());
        $productVisitHistory = $productVisitHistoryRepository->store($productVisitHistory);

        // dump($productVisitHistory);exit;

        return $productVisitHistory;
    }

    public static function findLast10() : array
    {
        App::getContainer()->wireService('WebshopPackage/repository/ProductVisitHistoryRepository');
        App::getContainer()->wireService('WebshopPackage/dataProvider/ProductDataProvider');
        $productVisitHistoryRepository = new ProductVisitHistoryRepository();
        $productVisitHistoryCollection = $productVisitHistoryRepository->findBy([
            'conditions' => [
                ['key' => 'website', 'value' => App::getWebsite()],
                ['key' => 'visitor_code', 'value' => App::getContainer()->getSession()->get('visitorCode')],
                // ['key' => 'special_purpose', 'operator' => 'NOT NULL'],
            ],
            'orderBy' => [['field' => 'updated_at', 'direction' => 'DESC']],
            'maxResults' => self::SEARCH_LIMIT
        ]);

        $productVisitHistoryDataSetPattern = [
            'id' => null,
            'product' => ProductDataProvider::getRawDataPattern(),
        ];

        $productVisitHistoryDataCollection = [];
        foreach ($productVisitHistoryCollection as $productVisitHistory) {
            $productVisitHistoryDataSet = $productVisitHistoryDataSetPattern;
            $productVisitHistoryDataSet['id'] = $productVisitHistory->getId();
            $productDataSet = ProductDataProvider::assembleDataSet($productVisitHistory->getProduct());
            $productVisitHistoryDataSet['product'] = $productDataSet;
            $productVisitHistoryDataCollection[] = $productVisitHistoryDataSet;
        }

        // dump($productVisitHistoryDataCollection);exit;
        // dump($productVisitHistoryCollection);
        return $productVisitHistoryDataCollection;
    }
}