<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class ProductCategoryRepository extends DbRepository
{
    public function isDeletable($id)
    {
        $stm = "SELECT pc.id
        from product_category pc 
        left join product p on p.product_category_id = pc.id 
        where pc.id = :id and p.id is not null
        group by pc.id";
        $params = array(':id' => $id);
        $dbm = $this->getDbManager();
        $result = $dbm->findOne($stm, $params);
        // dump($result);exit;
        $return = $result === false ? true : false;

        if ($return) {
            $stm2 = "SELECT id from product_category where product_category_id = :id ";
            $result2 = $dbm->findOne($stm2, $params);
            // dump($result2);
            $return = $result2 === false ? true : false;
        }

        return $return;
    }

    public function getCategoriesData($locale, $status = 1, $isIndependent = 0)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
    
        $stm = "SELECT 
            pc.id as category_id,
            pc.product_category_id as parent_category_id,
            pc." . ($locale == 'en' ? 'name_en' : 'name') . " as category_name,
            pc." . ($locale == 'en' ? 'slug_en' : 'slug') . " as category_slug,
            pc.code as category_code ,
            COUNT(DISTINCT p.id) as products_count ,
            COUNT(DISTINCT p_sub.id) as sub_products_count 
        FROM product_category pc 
            LEFT JOIN product p ON p.product_category_id = pc.id 
            LEFT JOIN product_category pc_sub ON pc_sub.product_category_id = pc.id 
            LEFT JOIN product p_sub ON p_sub.product_category_id = pc_sub.id 
        WHERE pc.status = :status AND pc.is_independent " . ($isIndependent === 1 ? '= 1' : '<> 1') . " 
        GROUP BY pc.id
        ";
        // dump($isIndependent);
        // dump(nl2br($stm));exit;

        $params = array(':status' => $status);
        $dbm = $this->getDbManager();
        $result = $dbm->findAll($stm, $params);

        // dump($result);
    
        $rawCategoriesData = []; // Itt tároljuk a kategóriákat hierarchikus struktúrában
    
        foreach ($result as $resultRow) {
            $categorySlug = $resultRow['category_slug'];
            $categoryLink = '';
            if (!$categorySlug) {
                $categoryLink = WebshopRequestService::assembleLink(['forceListAll' => true]);
            } else {
                $categoryLink = WebshopRequestService::assembleLink(['categorySlug' => $categorySlug]);
            }
    
            $categoryData = [
                'id' => $resultRow['category_id'],
                'parentCategoryId' => $resultRow['parent_category_id'],
                'displayedName' => $resultRow['category_name'],
                'link' => '/'.$categoryLink,
                'productsCount' => $resultRow['products_count'] + $resultRow['sub_products_count'],
            ];
    
            $rawCategoriesData[] = $categoryData;
        }
    
        // Hívjuk meg a kategóriák rendezésére a rekurzív metódust
        $hierarchicalCategories = self::arrangeCategories($rawCategoriesData);
    
        return $hierarchicalCategories;
    }

    public static function arrangeCategories($categoriesData, $parentId = null)
    {
        $result = [];
    
        foreach ($categoriesData as $categoryData) {
            if ($categoryData['parentCategoryId'] === $parentId) {
                $subdata = self::arrangeCategories($categoriesData, $categoryData['id']);
                if (!empty($subdata)) {
                    $categoryData['subdata'] = $subdata;
                }
                $result[] = $categoryData;
            }
        }
    
        return $result;
    }
    
    // public static function arrangeCategories($categories)
    // {
    //     $categoryTree = [];
    
    //     // Csoportosítjuk a kategóriákat szülők alapján
    //     $categoryMap = [];
    //     foreach ($categories as $category) {
    //         $categoryMap[$category['id']] = $category;
    //     }
    
    //     // Kategóriákat helyezünk a megfelelő szülők alá
    //     foreach ($categories as $category) {
    //         $parentCategoryId = $category['parentCategoryId'];
    //         if ($parentCategoryId === null) {
    //             // Főkategória
    //             $categoryTree[] = $category;
    //         } elseif (isset($categoryMap[$parentCategoryId])) {
    //             // A szülő kategória már szerepel a listában
    //             if (!isset($categoryMap[$parentCategoryId]['subdata'])) {
    //                 $categoryMap[$parentCategoryId]['subdata'] = [];
    //             }
    //             $categoryMap[$parentCategoryId]['subdata'][] = $category;
    //         }
    //     }
    
    //     return $categoryTree;
    // }

    // public function getCategoriesData_OLD($locale, $status = 1, $isIndependent = 0)
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');

    //     $stm = "SELECT 
    //         pc.id as category_id,
    //         pc.product_category_id as parent_category_id,
    //         pc.".($locale == 'en' ? 'name_en' : 'name')." as category_name,
    //         pc.".($locale == 'en' ? 'slug_en' : 'slug')." as category_slug,
    //         pc.code as category_code
    //     FROM product_category pc 
    //     -- left join product p on p.product_category_id = pc.id 
    //     WHERE pc.status = :status AND p.is_independent ".($isIndependent === 1 ? '<> 1' : '= 1')."
    //     -- GROUP BY pc.id
    //     ";
    //     $params = array(':status' => $status);
    //     $dbm = $this->getDbManager();
    //     $result = $dbm->findAll($stm, $params);
    //     $return = [];
    //     // $counter = 0;
    //     foreach ($result as $resultRow) {
    //         $categorySlug = $resultRow['category_slug'];
    //         $categoryLink = '';
    //         if (!$categorySlug) {
    //             $categoryLink = WebshopRequestService::assembleLink(['forceListAll' => true]);
    //         } else {
    //             $categoryLink = WebshopRequestService::assembleLink(['categorySlug' => $categorySlug]);
    //         }
    //         $return[] = [
    //             'id' => $resultRow['category_id'],
    //             'displayedName' => $resultRow['category_name'],
    //             'link' => $categoryLink
    //         ];
    //     }

    //     return $return;
    // }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT 
                            maintable.id, maintable.name as product_category, 
                            parent.name as parent_product_category, 
                            -- maintable.is_independent, 
                            maintable.status
                            FROM ".$this->getTableName()." maintable
                            LEFT JOIN product_category parent ON parent.id = maintable.product_category_id 
                            WHERE maintable.website = '".App::getWebsite()."') table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function findAllOnWebsite()
    {
        return $this->findBy(['conditions' => [['key' => 'website', 'value' => App::getWebsite()]]]);
        // $stm = "SELECT pc.id FROM product_category pc WHERE pc.website = :website ";
        // $params = array(':website' => App::getWebsite());
        // $dbm = $this->getDbManager();
        // $result = $dbm->findAll($stm, $params);
        // foreach 
    }
}
