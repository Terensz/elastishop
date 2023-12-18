<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\ToolPackage\service\TextAnalist;
use framework\packages\WebshopPackage\repository\ProductImageRepository;

class ProductRepository_COPY_231110 extends DbRepository
{
    public function isDeletable($id)
    {
        $dbm = $this->getDbManager();

        $stm = "SELECT count(b.id) as cnt
        from product a
        left join cart_item b on b.product_id = a.id 
        where a.id = :id 
        ";
        $params = array(':id' => $id);
        $result = $dbm->findOne($stm, $params);
        // dump($result);dump('cart_item');exit;
        if ($result['cnt'] > 0) {
            return false;
        }

        // $stm = "SELECT count(b.id) as cnt
        // from product a
        // left join invoice_item b on b.product_id = a.id 
        // where a.id = :id 
        // ";
        // $params = array(':id' => $id);
        // $result = $dbm->findOne($stm, $params);
        // // dump($result);
        // if ($result['cnt'] > 0) {
        //     return false;
        // }

        $stm = "SELECT count(b.id) as cnt
        from product a
        left join product_image b on b.product_id = a.id 
        where a.id = :id 
        ";
        $params = array(':id' => $id);
        $result = $dbm->findOne($stm, $params);
        if ($result['cnt'] > 0) {
            return false;
        }

        $stm = "SELECT count(b.id) as cnt
        from product a
        left join product_price b on b.product_id = a.id 
        where a.id = :id 
        ";
        $params = array(':id' => $id);
        $result = $dbm->findOne($stm, $params);
        if ($result['cnt'] > 0) {
            return false;
        }

        $stm = "SELECT count(b.id) as cnt
        from product a
        left join product_price_active b on b.product_id = a.id 
        where a.id = :id 
        ";
        $params = array(':id' => $id);
        $result = $dbm->findOne($stm, $params);
        if ($result['cnt'] > 0) {
            return false;
        }

        $stm = "SELECT count(b.id) as cnt
        from product a
        left join shipment_item b on b.product_id = a.id 
        where a.id = :id 
        ";
        $params = array(':id' => $id);
        $result = $dbm->findOne($stm, $params);
        if ($result['cnt'] > 0) {
            return false;
        }

        return true;
    }

    /**
     * @var $locale: locale. "en" or anything else.
     * @var $administratorMode: in administrator mode the list will contain the anomalous or inactive product.
    */
    // public function getProductsData($locale, $showAnomalous = false, $showInactive = false)
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
    
    //     $stm = "SELECT 
    //         p.id as product_id,
    //         p.product_category_id as category_id,
    //          as product_condition,
    //         p." . ($locale == 'en' ? 'name_en' : 'name') . " as product_name,
    //         p." . ($locale == 'en' ? 'slug_en' : 'slug') . " as product_slug,
    //         p.code as product_code 
    //     FROM product p 
    //     WHERE p.status = :status 
        
    //     ";
    //     // dump($isIndependent);
    //     // dump(nl2br($stm));

    //     $params = array(':status' => $status); 
    //     $dbm = $this->getDbManager();
    //     $result = $dbm->findAll($stm, $params);

    //     // dump($result);
    
    //     $rawCategoriesData = []; // Itt tároljuk a kategóriákat hierarchikus struktúrában
    
    //     foreach ($result as $resultRow) {
    //         $categorySlug = $resultRow['category_slug'];
    //         $categoryLink = '';
    //         if (!$categorySlug) {
    //             $categoryLink = WebshopRequestService::assembleLink(['forceListAll' => true]);
    //         } else {
    //             $categoryLink = WebshopRequestService::assembleLink(['categorySlug' => $categorySlug]);
    //         }
    
    //         $categoryData = [
    //             'id' => $resultRow['category_id'],
    //             'parentCategoryId' => $resultRow['parent_category_id'],
    //             'displayedName' => $resultRow['category_name'],
    //             'link' => $categoryLink
    //         ];
    
    //         $rawCategoriesData[] = $categoryData;
    //     }
    // }

    public function getMainImage($id)
    {
        $this->wireService('WebshopPackage/repository/ProductImageRepository');
        $productImageRepo = new ProductImageRepository();
        $mainImage = $productImageRepo->findOneBy(['conditions' => [
            ['key' => 'product_id', 'value' => $id], 
            ['key' => 'main', 'value' => 1]
        ]]);

        return $mainImage;
    }

    public function getSecondaryImages($id)
    {
        $this->wireService('WebshopPackage/repository/ProductImageRepository');
        $productImageRepo = new ProductImageRepository();
        $images = $productImageRepo->findBy(['conditions' => [
            ['key' => 'product_id', 'value' => $id], 
            ['key' => 'main', 'value' => 1, 'operator' => '<>']
        ]]);

        //dump($images);//exit;

        return $images;
    }

    private function assembleAccurateTermWhereParts($term)
    {
        $termWhereString = "";
        $termWhereString .= " p.name like :name ";
        // $where .= " OR p.name like :name".$counter." ";
        $termWhereString .= " OR p.name_en like :name_en ";
        $termWhereString .= " OR p.description like :description ";
        $termWhereString .= " OR p.description_en like :description_en ";
        $termWhereString .= " OR p.code like :code ";
        $termWhereString .= " OR pc.name like :categoryName ";
        $params = array(
            'name' => $term,
            'name_en' => $term,
            'description' => $term,
            'description_en' => $term,
            'code' => $term,
            'categoryName' => $term
        );

        $return = [
            'termWhereString' => $termWhereString,
            'params' => $params
        ];

        return $return;
    }

    private function assembleInaccurateTermWhereParts($term)
    {
        $params = array();
        $termWhereString = "";

        if ($term) {
            App::getContainer()->wireService('ToolPackage/service/TextAnalist');
            $textAnalist = new TextAnalist();
            $similarities = $textAnalist->getSimilarities($term);

            $counter = 0;
            foreach ($similarities as $similarity) {
                $termWhereString .= ($counter == 0 ? "" : " OR ")." p.name like :name".$counter." ";
                // $where .= " OR p.name like :name".$counter." ";
                $termWhereString .= " OR p.name_en LIKE :name_en".$counter." ";
                $termWhereString .= " OR p.description LIKE :description".$counter." ";
                $termWhereString .= " OR p.description_en LIKE :description_en".$counter." ";
                $termWhereString .= " OR p.code LIKE :code".$counter." ";
                $termWhereString .= " OR pc.name LIKE :categoryName".$counter." ";
                $params = array_merge($params, array(
                    'name'.$counter => '%'.$similarity.'%',
                    'name_en'.$counter => '%'.$similarity.'%',
                    'description'.$counter => '%'.$similarity.'%',
                    'description_en'.$counter => '%'.$similarity.'%',
                    'code'.$counter => '%'.$similarity.'%',
                    'categoryName'.$counter => '%'.$similarity.'%',
                ));
                $counter++;
            }
        }

        $return = [
            'termWhereString' => $termWhereString,
            'params' => $params
        ];

        return $return;
    }

    private function searcher($term, $stmBase, $preTermWhereParams = [], $afterTermWhereParams = [])
    {

        $termWhereString = "";
        $termParams = [];
        $params = [];
        if ($term) {
            $termWhereParts = $this->assembleAccurateTermWhereParts($term);
            $termWhereString = empty($termWhereParts['termWhereString']) ? "" : " AND (".$termWhereParts['termWhereString'].")";
            $termParams = $termWhereParts['params'];
        }
        $params = array_merge($preTermWhereParams, $termParams);
        $params = array_merge($params, $afterTermWhereParams);
        $stm = str_replace('[termWhereString]', $termWhereString, $stmBase);
        $objects = $this->searcherObjectWrapper($stm, $params);

        if (empty($objects) && $term) {
            $termWhereParts = $this->assembleInaccurateTermWhereParts($term);
            $termWhereString = empty($termWhereParts['termWhereString']) ? "" : " AND (".$termWhereParts['termWhereString'].")";
            $stm = str_replace('[termWhereString]', $termWhereString, $stmBase);
            $params = array_merge($preTermWhereParams, $termWhereParts['params']);
            $params = array_merge($params, $afterTermWhereParams);
            $objects = $this->searcherObjectWrapper($stm, $params);
        }

        // dump($stmBase);
        // dump($params);

        return $objects;
    }

    private function searcherObjectWrapper($stm, $params)
    {
        $dbm = $this->getDbManager();
        $result = $dbm->findAll($stm, $params);
        $ids = $this->unwrapIds($result);

        $objects = array();

        foreach ($ids as $id) {
            $objects[] = $this->find($id);
        }

        return $objects;
    }

    public function searchCategory($term, $categorySlug = null, $english = null, $page = null)
    {
        $params = [];

        $categoryWhereString = "";
        if ($categorySlug) {
            $categorySlugFieldName = 'slug'.($english ? '_en' : '');
            $categoryWhereString = " AND pc.".$categorySlugFieldName." = :categorySlug ";
            $params['categorySlug'] = $categorySlug;
        }

        $stm = "SELECT p.id
        FROM product p 
        LEFT JOIN product_category pc ON pc.id = p.product_category_id
        WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
        AND (pc.is_independent IS NULL OR pc.is_independent = 0)
        AND pc.status = 1 AND p.status = 1 [termWhereString] " .$categoryWhereString."
        GROUP BY p.id 
        ORDER BY p.name , p.name_en ";

        return $this->searcher($term, $stm, [], $params);
    }

    public function searchAll($term)
    {
        $stm = "SELECT p.id
        FROM product p 
        LEFT JOIN product_category pc ON pc.id = p.product_category_id
        WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
        AND (pc.is_independent IS NULL OR pc.is_independent = 0)
        AND pc.status = 1 AND p.status = 1 [termWhereString] 
        GROUP BY p.id 
        ORDER BY p.name , p.name_en ";

        return $this->searcher($term, $stm);
    }

    public function searchDiscounted($term, $page = null)
    {
        $stm = "SELECT p.id
        FROM product p 
        INNER JOIN product_category pc ON pc.id = p.product_category_id
        INNER JOIN product_price_active ppa ON ppa.product_id = p.id
        INNER JOIN product_price pp on pp.id = ppa.product_price_id
        WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
        AND (pc.is_independent IS NULL OR pc.is_independent = 0)
        AND pc.status = 1 AND p.status = 1 [termWhereString] 
        AND pp.price_type = 'discount' 
        ORDER BY p.name , p.name_en ";

        return $this->searcher($term, $stm);
    }

    public function getMostPopularProducts($page = null)
    {

        return [];
    }

    public function unwrapIds($findAllResult)
    {
        $result = array();
        foreach ($findAllResult as $findAllResultRow) {
            $result[] = $findAllResultRow['id'];
        }
        return $result;
    }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.name, ".($this->productCodeExists() ? 'maintable.code, ' : '')." pcat.name as product_category, maintable.status
                            FROM ".$this->getTableName()." maintable
                            LEFT JOIN product_category pcat ON pcat.id = maintable.product_category_id 
                            WHERE maintable.website = '".App::getWebsite()."') table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function productCodeExists()
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT id FROM product WHERE website = '".App::getWebsite()."' AND code IS NOT NULL AND code <> '' LIMIT 1 ";
        $res = $dbm->findOne($stm, []);
        return $res ? true : false;
    }
}
