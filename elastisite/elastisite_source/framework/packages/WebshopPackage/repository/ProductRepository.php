<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\ToolPackage\service\TextAnalist;
use framework\packages\WebshopPackage\entity\Product;
use framework\packages\WebshopPackage\entity\ProductPrice;
use framework\packages\WebshopPackage\repository\ProductImageRepository;
use framework\packages\WebshopPackage\service\WebshopRequestService;
use framework\packages\WebshopPackage\service\WebshopService;

class ProductRepository extends DbRepository
{
    const SEARCH_ACCURACY_ACCURATE = 'Accurate';
    const SEARCH_ACCURACY_INACCURATE = 'Inaccurate';

    const PRODUCT_CONDITION_OFFERABLE = 'Offerable';
    const PRODUCT_CONDITION_ANOMALOUS = 'Anomalous';

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
     * @var $term: the search term.
     * @var $showAnomalous: products lacking price, or price is too low.
     * @var $showInactive
    */
    public function getProductsData(string $locale, array $filter = [], array $options = [])
    {
        $dbm = $this->getDbManager();
        App::getContainer()->wireService('WebshopPackage/entity/Product');
        App::getContainer()->wireService('WebshopPackage/entity/ProductPrice');
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');

        // dump($filter);
        /**
         * Setting up filter
        */
        // $categorySlug = !isset($filter['categorySlug']) ? null : $filter['categorySlug'];
        // $searchTerm = !isset($filter['searchTerm']) ? null : $filter['searchTerm'];
        // $status = !isset($filter['status']) ? Product::STATUS_ACTIVE : $filter['status'];

        /**
         * Setting up options
        */
        $page = isset($options['page']) ? $options['page'] : 1;
        $getDescription = isset($options['getDescription']) ? $options['getDescription'] : false;
        $maxItemsOnPage = isset($options['maxItemsOnPage']) ? $options['maxItemsOnPage'] : WebshopService::getSetting('WebshopPackage_maxProductsOnPage');
        $showAnomalous =  isset($options['showAnomalous']) ? $options['showAnomalous'] : false;
        $showInactive = isset($options['showInactive']) ? $options['showInactive'] : false;
        $offset = ($page - 1) * $maxItemsOnPage;
        
        $stm = "
        SELECT 
            *
        FROM (
        ".self::getProductsDataQueryBase($locale, $getDescription)."
        ) core_query 
        WHERE 
            (product_condition = '".self::PRODUCT_CONDITION_OFFERABLE."'".($showAnomalous ? " OR product_condition = '".self::PRODUCT_CONDITION_ANOMALOUS."'" : "").")
            AND (product_status = '".Product::STATUS_ACTIVE."'".($showInactive ? " OR product_status = '".Product::STATUS_INACTIVE."'" : "").")
            [categoryOuterFilter]
        LIMIT ".$offset.", ".$maxItemsOnPage."
        ";
        // if (isset(['specialCategorySlugKey']) {
        // }

        $query = $this->innerQueryConditionsAssembler($locale, $filter, $stm, self::SEARCH_ACCURACY_ACCURATE);
        $stm = $query['statement'];
        $params = $query['params'];

        $categoryOuterFilter = '';
        if (isset($filter['specialCategorySlugKey']) && !empty($filter['specialCategorySlugKey'])) {
            if ($filter['specialCategorySlugKey'] == WebshopService::TAG_RECOMMENDED_PRODUCTS) {
                $categoryOuterFilter = "AND (is_recommended = :is_recommended OR ppl_gross > ppa_gross) ";
                $params = array_merge($params, ['is_recommended' => Product::IS_RECOMMENDED_YES]);
                // dump($params);
            }
        }
        $stm = str_replace('[categoryOuterFilter]', $categoryOuterFilter, $stm);

        // dump($query['params']);
        // dump(nl2br($query['statement']));exit;
        // dump(nl2br($stm));
        // dump($params);

        $result = $dbm->findAll($stm, $params);

        // dump($query['params']);
        // dump($result);exit;

        if (empty($result)) {
            $query = $this->innerQueryConditionsAssembler($locale, $filter, $stm, self::SEARCH_ACCURACY_INACCURATE);   
            $result = $dbm->findAll($stm, $params);
        }

        // dump($result);exit;
        return $result;
    }

    public static function getProductsDataQueryBase(string $locale, bool $getDescription) : string
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        App::getContainer()->wireService('WebshopPackage/entity/ProductPrice');

        $stm = "        SELECT 
                            null as 'unique_key',
                            null as 'quantity',
                            p.id as product_id,
                            p.special_purpose as product_special_purpose,
                            p.is_recommended as is_recommended,
                            p.product_category_id as category_id,
                            CASE
                                WHEN p.code IS NULL OR p.code = '' THEN p.id
                                ELSE p.code
                            END AS product_sku ,
                            CASE
                            WHEN 
                                (
                                    GROUP_CONCAT(ppa_binder.id) IS NULL 
                                        OR GROUP_CONCAT(ppa.gross_price) IS NULL 
                                        OR GROUP_CONCAT(ppa.gross_price) <= 0
                                        OR GROUP_CONCAT(ppa.vat) IS NULL 
                                        -- OR GROUP_CONCAT(ppa.vat) <= 0 
                                )THEN '".self::PRODUCT_CONDITION_ANOMALOUS."'
                            ELSE '".self::PRODUCT_CONDITION_OFFERABLE."'
                            END as product_condition,
                            pc." . ($locale == 'en' ? 'name_en' : 'name') . " as category_name,
                            p." . ($locale == 'en' ? 'name_en' : 'name') . " as product_name,
                            p." . ($locale == 'en' ? 'short_info_en' : 'short_info') . " as product_short_info,
                            ".($getDescription ? "p.description".($locale == 'en' ? '_en' : '') : "''")." as product_description,
                            p." . ($locale == 'en' ? 'slug_en' : 'slug') . " as product_slug,
                            p.status as product_status,
                            p.code as product_code,
                            GROUP_CONCAT(DISTINCT cur_ppl.code) as ppl_currency_code,
                            GROUP_CONCAT(DISTINCT ppl.price_type) as ppl_price_type,
                            GROUP_CONCAT(DISTINCT ppl.net_price) as ppl_net, 
                            GROUP_CONCAT(DISTINCT ppl.gross_price) as ppl_gross, 
                            GROUP_CONCAT(DISTINCT ppl.vat) as ppl_vat,
                            SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT ppa_binder.id), ',', 1) as ppa_binder_id,
                            SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT ppa_binder.product_price_id), ',', 1) as ppa_binder_product_price_id,
                            GROUP_CONCAT(DISTINCT cur_ppa.code) as ppa_currency_code,
                            GROUP_CONCAT(DISTINCT ppa.price_type) as ppa_price_type,
                            GROUP_CONCAT(DISTINCT ppa.net_price) as ppa_net, 
                            GROUP_CONCAT(DISTINCT ppa.gross_price) as ppa_gross, 
                            GROUP_CONCAT(DISTINCT ppa.vat) as ppa_vat,
                            CONCAT('".($locale == 'en' ? WebshopRequestService::getShowProductLinkBase('en') : WebshopRequestService::getShowProductLinkBase($locale))."' , p." . ($locale == 'en' ? 'slug_en' : 'slug') . ") as product_info_link,
                            GROUP_CONCAT(DISTINCT CONCAT(pi.slug , '[main]' , pi.main) SEPARATOR '[separator]') as product_image_slugs
                        FROM product p 
                        LEFT JOIN product_category pc ON pc.id = p.product_category_id 
                        LEFT JOIN product_price ppl ON (ppl.product_id = p.id AND ppl.price_type = '".ProductPrice::PRICE_TYPE_LIST."') -- List (ppl) 
                        LEFT JOIN currency cur_ppl ON cur_ppl.id = ppl.currency_id 
                        LEFT JOIN product_price_active ppa_binder ON ppa_binder.product_id = p.id 
                        LEFT JOIN product_price ppa ON ppa.id = ppa_binder.product_price_id -- Active (ppa)
                        LEFT JOIN currency cur_ppa ON cur_ppa.id = ppa.currency_id 
                        LEFT JOIN product_image pi ON pi.product_id = p.id
                        WHERE p.website = '".App::getWebsite()."' 
                        AND (pc.id IS NULL OR pc.website = '".App::getWebsite()."')
                        AND (p.special_purpose IS NULL OR p.special_purpose = '')
                        [statusWhereString]
                        [categoryWhereString]
                        [productIdsWhereString]
                        [termWhereString] 
                        GROUP BY p.id 
                        -- ORDER BY pc.id ASC
        ";
        
        return $stm;
    }

    /**
     * @todo: a vegen, ha lesz kedv es igeny erre, akkor.
    */
    // public function searchCategory($term, $categorySlug = null, $english = null, $page = null)
    // {
    //     $params = [];

    //     $categoryWhereString = "";
    //     if ($categorySlug) {
    //         $categorySlugFieldName = 'slug'.($english ? '_en' : '');
    //         $categoryWhereString = " AND pc.".$categorySlugFieldName." = :categorySlug ";
    //         $params['categorySlug'] = $categorySlug;
    //     }

    //     $stm = "SELECT p.id
    //     FROM product p 
    //     LEFT JOIN product_category pc ON pc.id = p.product_category_id
    //     WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
    //     AND (pc.is_independent IS NULL OR pc.is_independent = 0)
    //     AND pc.status = 1 AND p.status = 1 [termWhereString] " .$categoryWhereString."
    //     GROUP BY p.id 
    //     ORDER BY p.name , p.name_en ";

    //     return $this->searcher($term, $stm, [], $params);
    // }

    private function innerQueryConditionsAssembler($locale, $filter, $stmBase, $accuracy = self::SEARCH_ACCURACY_ACCURATE)
    {
        App::getContainer()->wireService('WebshopPackage/service/WebshopService');
        /**
         * Setting up filter
        */
        $categoryId = !isset($filter['categoryId']) ? null : $filter['categoryId'];
        $searchTerm = !isset($filter['searchTerm']) ? null : $filter['searchTerm'];
        $status = !isset($filter['status']) ? Product::STATUS_ACTIVE : $filter['status'];
        $productId = !isset($filter['productId']) ? null : $filter['productId'];
        $productIds = !isset($filter['productIds']) ? null : $filter['productIds'];

        $stm = $stmBase;
        $params = [];

        # Not in use yet
        // $params = array_merge($preTermWhereParams, $termParams);

        # Resolving [statusWhereString] 
        $statusWhereString = "AND p.status = :status ";
        $params = array_merge($params, ['status' => $status]);
        $stm = str_replace('[statusWhereString]', $statusWhereString, $stm);

        # Resolving [categoryWhereString]
        $categoryWhereString = "";
        if ($categoryId && !$productId && !$productIds) {
            $categoryWhereString = "AND (pc.id = :category_id OR pc.product_category_id = :category_id)";
            $params = array_merge($params, ['category_id' => $categoryId]);
        }
        $stm = str_replace('[categoryWhereString]', $categoryWhereString, $stm);

        # Handling productIds
        $productIdsWhereString = "";
        if (!empty($productIds)) {
            $productIdCounter = 1;
            $productIdsPlaceholders = [];
            foreach ($productIds as $productId) {
                $paramName = ":product_id_$productIdCounter";
                $productIdsPlaceholders[] = $paramName;
                $params[$paramName] = $productId;
                $productIdCounter++;
            }
    
            if (!empty($productIdsPlaceholders)) {
                $productIdsWhereString = " AND p.id IN (" . implode(', ', $productIdsPlaceholders) . ")";
            }

            // dump($productIdsWhereString);
            // dump($params);exit;
        }
        $stm = str_replace('[productIdsWhereString]', $productIdsWhereString, $stm);

        # Resolving [termWhereString]
        $termWhereString = "";
        $termParams = [];
        if ($productId && !$productIds) {
            $termWhereString = " AND p.id = :product_id ";
            $termParams = [
                'product_id' => $productId
            ];
        } elseif ($searchTerm) {
            $termWhereParts = $accuracy == self::SEARCH_ACCURACY_ACCURATE ? $this->assembleAccurateSearchTermWhereParts($searchTerm) : $this->assembleInaccurateSearchTermWhereParts($searchTerm);
            $termWhereString = empty($termWhereParts['termWhereString']) ? "" : " AND (".$termWhereParts['termWhereString'].")";
            $termParams = $termWhereParts['params'];
        }

        $params = array_merge($params, $termParams);
        $stm = str_replace('[termWhereString]', $termWhereString, $stm);

        # Not in use yet
        // $params = array_merge($params, $afterTermWhereParams);

        return [
            'statement' => $stm,
            'params' => $params
        ];
    }

    private function assembleAccurateSearchTermWhereParts($term)
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
            'name' => '%'.$term.'%',
            'name_en' => '%'.$term.'%',
            'description' => '%'.$term.'%',
            'description_en' => '%'.$term.'%',
            'code' => '%'.$term.'%',
            'categoryName' => '%'.$term.'%'
        );

        $return = [
            'termWhereString' => $termWhereString,
            'params' => $params
        ];

        return $return;
    }

    private function assembleInaccurateSearchTermWhereParts($term)
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

    // private function searcher_OLD($term, $stmBase, $preTermWhereParams = [], $afterTermWhereParams = [])
    // {

    //     $termWhereString = "";
    //     $termParams = [];
    //     $params = [];
    //     if ($term) {
    //         $termWhereParts = $this->assembleAccurateTermWhereParts($term);
    //         $termWhereString = empty($termWhereParts['termWhereString']) ? "" : " AND (".$termWhereParts['termWhereString'].")";
    //         $termParams = $termWhereParts['params'];
    //     }
    //     $params = array_merge($preTermWhereParams, $termParams);
    //     $params = array_merge($params, $afterTermWhereParams);
    //     $stm = str_replace('[termWhereString]', $termWhereString, $stmBase);
    //     $objects = $this->searcherObjectWrapper($stm, $params);

    //     if (empty($objects) && $term) {
    //         $termWhereParts = $this->assembleInaccurateTermWhereParts($term);
    //         $termWhereString = empty($termWhereParts['termWhereString']) ? "" : " AND (".$termWhereParts['termWhereString'].")";
    //         $stm = str_replace('[termWhereString]', $termWhereString, $stmBase);
    //         $params = array_merge($preTermWhereParams, $termWhereParts['params']);
    //         $params = array_merge($params, $afterTermWhereParams);
    //         $objects = $this->searcherObjectWrapper($stm, $params);
    //     }

    //     return $objects;
    // }

    /**
     * Ez el fog tunni, mert nem objektumozunk.
    */
    // private function searcherObjectWrapper($stm, $params)
    // {
    //     $dbm = $this->getDbManager();
    //     $result = $dbm->findAll($stm, $params);
    //     $ids = $this->unwrapIds($result);

    //     $objects = array();

    //     foreach ($ids as $id) {
    //         $objects[] = $this->find($id);
    //     }

    //     return $objects;
    // }

    // public function searchCategory($term, $categorySlug = null, $english = null, $page = null)
    // {
    //     $params = [];

    //     $categoryWhereString = "";
    //     if ($categorySlug) {
    //         $categorySlugFieldName = 'slug'.($english ? '_en' : '');
    //         $categoryWhereString = " AND pc.".$categorySlugFieldName." = :categorySlug ";
    //         $params['categorySlug'] = $categorySlug;
    //     }

    //     $stm = "SELECT p.id
    //     FROM product p 
    //     LEFT JOIN product_category pc ON pc.id = p.product_category_id
    //     WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
    //     AND (pc.is_independent IS NULL OR pc.is_independent = 0)
    //     AND pc.status = 1 AND p.status = 1 [termWhereString] " .$categoryWhereString."
    //     GROUP BY p.id 
    //     ORDER BY p.name , p.name_en ";

    //     return $this->searcher($term, $stm, [], $params);
    // }

    // public function searchAll($term)
    // {
    //     $stm = "SELECT p.id
    //     FROM product p 
    //     LEFT JOIN product_category pc ON pc.id = p.product_category_id
    //     WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
    //     AND (pc.is_independent IS NULL OR pc.is_independent = 0)
    //     AND pc.status = 1 AND p.status = 1 [termWhereString] 
    //     GROUP BY p.id 
    //     ORDER BY p.name , p.name_en ";

    //     return $this->searcher($term, $stm);
    // }

    // public function searchDiscounted($term, $page = null)
    // {
    //     $stm = "SELECT p.id
    //     FROM product p 
    //     INNER JOIN product_category pc ON pc.id = p.product_category_id
    //     INNER JOIN product_price_active ppa ON ppa.product_id = p.id
    //     INNER JOIN product_price pp on pp.id = ppa.product_price_id
    //     WHERE p.website = '".App::getWebsite()."' AND pc.website = '".App::getWebsite()."'
    //     AND (pc.is_independent IS NULL OR pc.is_independent = 0)
    //     AND pc.status = 1 AND p.status = 1 [termWhereString] 
    //     AND pp.price_type = 'discount' 
    //     ORDER BY p.name , p.name_en ";

    //     return $this->searcher($term, $stm);
    // }

    public function getMostPopularProducts($page = null)
    {

        return [];
    }

    /**
     * This is the query method for the datagrid. 
    */
    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT 
                                maintable.id, 
                                maintable.name, 
                                ".($this->productCodeExists() ? 'maintable.code, ' : '')." pcat.name as product_category,
                                maintable.special_purpose,
                                maintable.is_recommended,
                                maintable.status
                            FROM ".$this->getTableName()." maintable
                            LEFT JOIN product_category pcat ON pcat.id = maintable.product_category_id 
                            WHERE maintable.website = '".App::getWebsite()."') table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    //

    public function unwrapIds($findAllResult)
    {
        $result = array();
        foreach ($findAllResult as $findAllResultRow) {
            $result[] = $findAllResultRow['id'];
        }
        return $result;
    }

    // public function findSpecialPurposeProducts()
    // {
    //     $dbm = $this->getDbManager();
    //     $stm = "SELECT id FROM product WHERE website = '".App::getWebsite()."' AND special_purpose == '".Product::SPECIAL_PURPOSE_DELIVERY_FEE."'";
    //     $rawRes = $dbm->findOne($stm, []);

    //     return $res ? true : false;
    // }

    public function productCodeExists()
    {
        $dbm = $this->getDbManager();
        $stm = "SELECT id FROM product WHERE website = '".App::getWebsite()."' AND code IS NOT NULL AND code <> '' LIMIT 1 ";
        $res = $dbm->findOne($stm, []);
        return $res ? true : false;
    }

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
}
