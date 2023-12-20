<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\kernel\utility\BasicUtils;
use framework\packages\WebshopPackage\entity\ProductPrice;
use framework\packages\WebshopPackage\entity\Shipment;
use framework\packages\WebshopPackage\service\WebshopRequestService;

class ShipmentRepository extends DbRepository
{
    public function isDeletable($id)
    {
        return true;
    }

    public static function createCode()
    {
        $code = BasicUtils::generateRandomString(24, 'alphanum_small');
        $isCode = self::isExistingCode($code);

        return $isCode ? self::createCode() : $code;
    }

    // public static function getShipmentProductData($locale, $whereConditions, array $shipmentIds)
    // {
    //     $dbm = App::getContainer()->getDbManager();
    //     App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
    //     $stm = self::getShipmentProductDataQueryBase($locale, false, $whereConditions);

    //     $shipmentIdPlaceholders = null;
    //     if (!empty($shipmentIds)) {
    //         $shipmentIdPlaceholders = implode(', ', array_map(function ($shipmentId, $index) {
    //             return ":shipment_id_" . $index;
    //         }, $shipmentIds, array_keys($shipmentIds)));
    //     }

    //     // dump($shipmentIds);
    //     // dump($shipmentIdPlaceholders);

    //     $params = [];
    //     foreach ($shipmentIds as $index => $shipmentId) {
    //         $params[":shipment_id_" . $index] = $shipmentId;
    //     }

    //     $shipmentConditionString = '';
    //     if ($shipmentIdPlaceholders) {
    //         $shipmentConditionString = "AND shi.shipment_id IN ({$shipmentIdPlaceholders})";
    //     }
    //     $stm = str_replace('[shipmentConditionString]', $shipmentConditionString, $stm);
    //     // dump(nl2br($stm));
    //     // dump($params);

    //     $result = $dbm->findAll($stm, $params);
    //     // dump($result);exit;
    //     return $result;
    // }

    /**
     * This is a part of self::getShipmentProductData().
     * Works similarly like the ProductRepository::getProductsData(), and returns a dataset with the same format.
    */
    // private static function getShipmentProductDataQueryBase(string $locale, bool $getDescription) : string
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
    //     App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
    //     App::getContainer()->wireService('WebshopPackage/entity/Shipment');
    //     App::getContainer()->wireService('WebshopPackage/entity/ProductPrice');

    //     $stm = "        SELECT 
    //                         -- GROUP_CONCAT(DISTINCT shi.id) as 'unique_key',
    //                         shi.id as 'unique_key',
    //                         shi.quantity as 'quantity',
    //                         p.id as product_id,
    //                         p.special_purpose as product_special_purpose,
    //                         p.product_category_id as category_id,
    //                         CASE
    //                             WHEN p.code IS NULL OR p.code = '' THEN p.id
    //                             ELSE p.code
    //                         END AS product_sku ,
    //                         -- GROUP_CONCAT(DISTINCT p.id) as product_id,
    //                         -- GROUP_CONCAT(DISTINCT p.product_category_id) as category_id,
    //                         'Unavailable' as product_condition,
    //                         pc." . ($locale == 'en' ? 'name_en' : 'name') . " as category_name,
    //                         p." . ($locale == 'en' ? 'name_en' : 'name') . " as product_name,
    //                         p." . ($locale == 'en' ? 'short_info_en' : 'short_info') . " as product_short_info,
    //                         ".($getDescription ? "p.description".($locale == 'en' ? '_en' : '') : "''")." as product_description,
    //                         p." . ($locale == 'en' ? 'slug_en' : 'slug') . " as product_slug,
    //                         p.status as product_status,
    //                         p.code as product_code,
    //                         GROUP_CONCAT(DISTINCT cur_ppl.code) as ppl_currency_code,
    //                         GROUP_CONCAT(DISTINCT ppl.price_type) as ppl_price_type,
    //                         GROUP_CONCAT(DISTINCT ppl.net_price) as ppl_net, 
    //                         GROUP_CONCAT(DISTINCT ppl.gross_price) as ppl_gross, 
    //                         GROUP_CONCAT(DISTINCT ppl.vat) as ppl_vat,
    //                         null as ppa_binder_id,
    //                         SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT ppa.id), ',', 1) as ppa_binder_product_price_id,
    //                         GROUP_CONCAT(DISTINCT cur_ppa.code) as ppa_currency_code,
    //                         GROUP_CONCAT(DISTINCT ppa.price_type) as ppa_price_type,
    //                         GROUP_CONCAT(DISTINCT ppa.net_price) as ppa_net, 
    //                         GROUP_CONCAT(DISTINCT ppa.gross_price) as ppa_gross, 
    //                         GROUP_CONCAT(DISTINCT ppa.vat) as ppa_vat,
    //                         CONCAT('".($locale == 'en' ? WebshopRequestService::getShowProductLinkBase('en') : WebshopRequestService::getShowProductLinkBase($locale))."' , p." . ($locale == 'en' ? 'slug_en' : 'slug') . ") as product_info_link,
    //                         GROUP_CONCAT(DISTINCT CONCAT(pi.slug , '[main]' , pi.main) SEPARATOR '[separator]') as product_image_slugs
    //                     FROM shipment_item shi 
    //                     LEFT JOIN product_price ppa ON ppa.id = shi.product_price_id 
    //                     LEFT JOIN product p ON p.id = shi.product_id
    //                     LEFT JOIN product_category pc ON pc.id = p.product_category_id 
    //                     LEFT JOIN product_price ppl ON (ppl.product_id = p.id AND ppl.price_type = '".ProductPrice::PRICE_TYPE_LIST."') -- List (ppl) 
    //                     LEFT JOIN currency cur_ppl ON cur_ppl.id = ppl.currency_id 
    //                     LEFT JOIN currency cur_ppa ON cur_ppa.id = ppa.currency_id 
    //                     LEFT JOIN product_image pi ON pi.product_id = p.id
    //                     WHERE p.website = '".App::getWebsite()."' 
    //                     AND (pc.id IS NULL OR pc.website = '".App::getWebsite()."')
    //                     [shipmentConditionString]
    //                     GROUP BY shi.id , p.id
    //                     ORDER BY shi.id ASC
    //     ";

    //     return $stm;
    // }
    
    public static function getLastShipmentId()
    {
        $stm = "SELECT id FROM shipment ORDER BY id DESC LIMIT 1 ";
        $dbm = App::getContainer()->getDbManager();
        $ret = $dbm->findOne($stm, []);

        return $ret ? $ret['id'] : null;
    }

    public static function getOrderedShipmentIds($orderByIdDirection = 'ASC', $limit = null)
    {
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
        $dbm = App::getContainer()->getDbManager();

        $stm = "SELECT id
        FROM shipment 
        WHERE website = '".App::getWebsite()."' AND status IN (
            :shipment_status_".Shipment::SHIPMENT_STATUS_ORDER_PREPARED." ,
            :shipment_status_".Shipment::SHIPMENT_STATUS_WAITING_FOR_PRODUCT." ,
            :shipment_status_".Shipment::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY." 
        )
        ORDER BY id ".$orderByIdDirection."
        ".($limit ? " LIMIT ".$limit : "");

        $ret = $dbm->findAll($stm, [
            'shipment_status_'.Shipment::SHIPMENT_STATUS_ORDER_PREPARED => Shipment::SHIPMENT_STATUS_ORDER_PREPARED,
            'shipment_status_'.Shipment::SHIPMENT_STATUS_WAITING_FOR_PRODUCT => Shipment::SHIPMENT_STATUS_WAITING_FOR_PRODUCT,
            'shipment_status_'.Shipment::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY => Shipment::SHIPMENT_STATUS_PREPARED_FOR_DELIVERY
        ]);
        // dump($stm);
        // dump($ret);exit;

        return $ret;
    }

    public function store($entity)
    {
        $entity = $this->handleTemporaryAccountOnStatusChange($entity);
        return parent::store($entity);
    }

    public function handleTemporaryAccountOnStatusChange($entity)
    {
        $this->wireService('WebshopPackage/entity/Shipment');
        $this->getContainer()->setService('WebshopPackage/service/WebshopService');
        $webshopService = $this->getContainer()->getService('WebshopService');

        if ($entity->getId() && in_array($entity->getStatus(), [Shipment::SHIPMENT_STATUS_ORDER_CANCELLED , Shipment::SHIPMENT_STATUS_DELIVERED])) {
            $entity->setClosed(1);
            $storedEntity = $this->find($entity->getId());
            if ($entity->getStatus() != $storedEntity->getStatus()) {
                if ($entity->getTemporaryAccount() && $entity->getTemporaryAccount()->getTemporaryPerson()) {
                    $tempPerson = $entity->getTemporaryAccount()->getTemporaryPerson();
                    $entity->getTemporaryAccount()->setTemporaryPerson(null);
                    // dump($entity);//exit;
                    // dump($tempPerson);
                    $tempPerson->getRepository()->remove($tempPerson->getId());
                    // dump('alma');exit;
                }
            }
        }
        return $entity;
    }

    public static function isExistingCode($code)
    {
        $stm = "SELECT id FROM shipment WHERE code = :code ";
        $params = array(':code' => $code);
        $dbm = App::getContainer()->getDbManager();
        $ret = $dbm->findOne($stm, $params);

        return $ret;
    }

    // public static function hasOrderWithSpecificStatuses(array $statuses)
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/Shipment');

    //     $stm = "SELECT COUNT(*) as total FROM shipment 
    //         WHERE website = '".App::getWebsite()."' 
    //         AND visitor_code = :visitor_code 
    //         AND status = :shipment_status_".$status;
    //     $params = array(
    //         ':shipment_status_'.$status => $status, 
    //         ':visitor_code' => App::getContainer()->getSession()->get('visitorCode'));
    //     $dbm = App::getContainer()->getDbManager();
    //     $ret = $dbm->findOne($stm, $params);
    //     return (int)$ret['total'] > 0 ? true : false;
    // }

    public static function hasShipmentWithSpecificStatuses(array $statuses, $visitorCode = null)
    {
        return count(self::getShipmentIdsCollectionWithSpecificStatuses($statuses, $visitorCode)) > 0 ? true : false ;
    }

    public static function getShipmentCollectionWithSpecificStatuses(array $statuses, $visitorCode = null)
    {
        $idsCollection = self::getShipmentIdsCollectionWithSpecificStatuses($statuses, $visitorCode);
        return self::createShipmentCollection($idsCollection);
    }

    public static function getShipmentCollectionFromId(int $shipmentId)
    {
        $idsCollection = [['shipment_id' => $shipmentId]];
        return self::createShipmentCollection($idsCollection);
    }

    /**
     * @var $idsCollection - an array like this: [['shipment_id' => 23456], ['shipment_id' => 26789]]
    */
    public static function createShipmentCollection(array $idsCollection) : array
    {
        $repository = new self();
        
        $objectCollection = [];
        $ids = [];
        foreach ($idsCollection as $idsCollectionRow) {
            $id = $idsCollectionRow['shipment_id'];
            $ids[] = $id;
            $objectCollection[] = $repository->find($id);
        }

        $result = [
            'objectCollection' => $objectCollection,
            'ids' => $ids
        ];

        return $result;
    }

    public static function getShipmentIdsCollectionWithSpecificStatuses(array $statuses, $visitorCode = null)
    {
        App::getContainer()->wireService('WebshopPackage/entity/Shipment');
    
        // Készít egy ":status_1, :status_2, ..." részt a lekérdezéshez
        $statusPlaceholders = implode(', ', array_map(function ($status, $index) {
            return ":status_" . $index;
        }, $statuses, array_keys($statuses)));
    
        $stm = "SELECT s.id as shipment_id 
            FROM shipment s
            WHERE s.website = '" . App::getWebsite() . "'";

        if ($visitorCode !== null) {
            $stm .= " AND s.visitor_code = :visitor_code";
            $params = array_merge([':visitor_code' => $visitorCode], array_combine(array_map(function ($index) {
                return ":status_" . $index;
            }, array_keys($statuses)), $statuses));
        } else {
            $params = array_combine(array_map(function ($index) {
                return ":status_" . $index;
            }, array_keys($statuses)), $statuses);
        }

        $stm .= " AND s.status IN ({$statusPlaceholders}) 
        ORDER BY s.priority DESC, s.id ASC ";
    
        $dbm = App::getContainer()->getDbManager();
        $result = $dbm->findAll($stm, $params);
    
        return $result;
    }

    // public static function getShipmentIdsCollectionWithSpecificStatuses(array $statuses, $visitorCode)
    // {
    //     App::getContainer()->wireService('WebshopPackage/entity/Shipment');
    
    //     // Készít egy ":status_1, :status_2, ..." részt a lekérdezéshez
    //     $statusPlaceholders = implode(', ', array_map(function ($status, $index) {
    //         return ":status_" . $index;
    //     }, $statuses, array_keys($statuses)));
    
    //     $stm = "SELECT s.id as shipment_id 
    //         FROM shipment s
    //         WHERE s.website = '" . App::getWebsite() . "' 
    //         AND s.visitor_code = :visitor_code 
    //         AND s.status IN ({$statusPlaceholders})";
    
    //     // Készít egy asszociatív tömböt a paraméterekhez
    //     $params = array_merge([':visitor_code' => ], array_combine(array_map(function ($index) {
    //         return ":status_" . $index;
    //     }, array_keys($statuses)), $statuses));
    
    //     $dbm = App::getContainer()->getDbManager();
    //     $result = $dbm->findAll($stm, $params);
    
    //     return $result;
    // }

    public function getGridDataFilteredQuery($filter)
    {
        $whereClause = $this->createWhereClauseFromFilter($filter ? $filter['conditions'] : null);
        return array(
            'statement' => "SELECT * FROM (SELECT maintable.id, maintable.code
                                , tp.mobile
                                , tp.email
                                -- , CONCAT(c.translation_reference,'.country') as country_name
                                -- , maintable.zip_code
                                -- , maintable.city
                                , maintable.status
                                , maintable.created_at
                            FROM ".$this->getTableName()." maintable
                            -- LEFT JOIN country c ON c.id = maintable.country_id
                            LEFT JOIN temporary_account ta ON ta.id = maintable.temporary_account_id
                            LEFT JOIN temporary_person tp ON tp.temporary_account_id = ta.id
                            WHERE website = '".App::getWebsite()."'
                            ) table0
                            ".$whereClause['whereStr']." ",
            'params' => $whereClause['params']
        );
    }

    public function remove($id)
    {
        $pdo = $this->getContainer()->getKernelObject('DbManager')->getConnection();
        $shipment = $this->find($id);
        $temporaryAccount = $shipment->getTemporaryAccount();
        // dump($pdo->inTransaction());
        // dump($shipment); exit;
		$pdo->beginTransaction();
		try {
            # Remove ShipmentItems
            $this->setService('WebshopPackage/repository/ShipmentItemRepository');
            $shipmentItemRepo = $this->getService('ShipmentItemRepository');
            $removedShipmentItems = $shipmentItemRepo->find_iterate_do('remove', 'shipment_id', $id);

            parent::remove($id);
			$pdo->commit();
            // dump($shipment); exit;
            // $pdo->rollback();

		} catch(\Exception $e) {
			$pdo->rollback();
		}

        $temporaryAccount->getRepository()->remove($temporaryAccount->getId());
    }
    
    public function removeAll($truncate = false)
    {
        return $this->find_iterate_do('remove');
    }
}
