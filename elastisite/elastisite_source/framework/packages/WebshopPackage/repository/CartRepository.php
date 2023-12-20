<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;
use framework\packages\UserPackage\repository\TemporaryAccountRepository;
use framework\packages\WebshopPackage\entity\ProductPrice;
use framework\packages\WebshopPackage\service\WebshopRequestService;

class CartRepository extends DbRepository
{
    public function isDeletable($id)
    {
        // $stm = "SELECT pc.id
        // from product p
        // left join order o on o.product_id = p.id 
        // where p.id = :id 
        // group by p.id";
        // $params = array(':id' => $id);
        // $dbm = $this->getDbManager();
        // $result = $dbm->findOne($stm, $params);
        // $return = $result === false ? true : false;
        return true;
    }

    public function countItems($cart)
    {
        $counter = 0;
        foreach ($cart->getCartItem() as $cartItem) {
            $counter += $cartItem->getQuantity();
        }
        return $counter;
    }

    public function rudeRemove($cartId)
    {
        $dbm = $this->getDbManager();
        $stm = "DELETE FROM cart_item WHERE cart_id = :cart_id ";
        $dbm->execute($stm, [
            'cart_id' => $cartId,
        ]);
        $stm = "DELETE FROM cart WHERE id = :cart_id ";
        $dbm->execute($stm, [
            'cart_id' => $cartId,
        ]);
        if ($this->getSession()->get('webshop_cartId') == $cartId) {
            $this->getSession()->set('webshop_cartId', null);
        }
    }

    public function storeTemporaryAccountId(int $cartId, int $temporaryAccountId)
    {
        if (!$cartId || !$temporaryAccountId) {
            return false;
        }
        $dbm = $this->getDbManager();
        $stm = "UPDATE cart SET temporary_account_id = :temporary_account_id WHERE id = :cart_id ";
        $dbm->execute($stm, [
            'temporary_account_id' => $temporaryAccountId,
            'cart_id' => $cartId,
        ]);
    }

    public function storeShipmentId(int $cartId, int $shipmentId)
    {
        if (!$cartId || !$shipmentId) {
            return false;
        }
        $dbm = $this->getDbManager();
        $stm = "UPDATE cart SET shipment_id = :shipment_id WHERE id = :cart_id ";
        $dbm->execute($stm, [
            'shipment_id' => $shipmentId,
            'cart_id' => $cartId,
        ]);
    }

    public function remove($id)
    {
        throw new \Exception('Never use this method! use removeObsolete instead!');
    }

    /**
     * This method is the only one you can use for removing a cart. "remove" will thorw you an exception.
     * 
     * This method:
     * - Can use a filter.
     * - Can be prompted for remove session cart or not.
     * - Removes all TemporaryAccounts and TemporaryPersons bound to the cart, if they were not assigned to a Shipment.
     * - Removes all cart items.
    */
    public static function removeObsolete(array $detailedQueryParams, $sessionCartIdIsUnremovable = true)
    {
        if ($sessionCartIdIsUnremovable) {
            $detailedQueryParams[] = ['refKey' => 'c.id', 'paramKey' => 'unremovable_cart_id', 'operator' => '<>', 'value' => App::getContainer()->getSession()->get('webshop_cartId')];
        }

        $dbm = App::getContainer()->getDbManager();
        $stm0 = "SELECT 
            c.id as cart_id,
            c.temporary_account_id as temporary_account_id 
        FROM cart c 
        ";

        $queryParams = [];
        $whereConditions = [];
        foreach ($detailedQueryParams as $detailedQueryParam) {
            if (is_string($detailedQueryParam) || !isset($detailedQueryParam['refKey'])) {
                // dump(nl2br($stm0));
                dump($detailedQueryParams);
            }
            $refKey = $detailedQueryParam['refKey'];
            $queryParamKey = $detailedQueryParam['paramKey'];
            $value = $detailedQueryParam['value'];
            $operator = isset($detailedQueryParam['operator']) ? $detailedQueryParam['operator'] : '=';
            $whereConditions[] = "$refKey $operator :$queryParamKey";
            $queryParams[$queryParamKey] = $value;
        }

        if (!empty($whereConditions)) {
            $stm0 .= "WHERE " . implode(" AND ", $whereConditions);
        }

        // dump('removeObsolete!!!');exit;

        $cartDataCollection = $dbm->findAll($stm0, $queryParams);
        
        App::getContainer()->wireService('UserPackage/repository/TemporaryAccountRepository');
        $temporaryAccountRepository = new TemporaryAccountRepository();

        if (is_array($cartDataCollection) && !empty($cartDataCollection)) {
            $params = array();
            $counter = 0;
            foreach ($cartDataCollection as $cartDataRow) {
                $cartId = $cartDataRow['cart_id'];
                $temporaryAccountId = $cartDataRow['temporary_account_id'];
                $temporaryAccountRepository->remove($temporaryAccountId);

                /**
                 * There can be multiple (2) obsolete carts of one user. We are removing them all, but first let's collect these ids.
                */
                $cartIdParams[':id'.$counter] = $cartId;

                // $cartObject = $this->find($cartId);
                $counter++;
            }

            /**
             * We are removing all items from the cart
            */
            $stmRemoveCartItems = "DELETE from cart_item where cart_id in (".implode(',', array_keys($cartIdParams)).")";
            $dbm->execute($stmRemoveCartItems, $cartIdParams);

            /**
             * And finally, removing the cart
            */
            $stmRemoveCarts = "DELETE from cart where id in (".implode(',', array_keys($cartIdParams)).")";
            $dbm->execute($stmRemoveCarts, $cartIdParams);
        }
    }

    // public static function getCartProductData($locale, $whereConditions, array $cartIds, $debug = false)
    // {
    //     $dbm = App::getContainer()->getDbManager();
    //     App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
    //     $stm = self::getCartProductDataQueryBase($locale, false, $whereConditions);

    //     $cartIdPlaceholders = null;
    //     if (!empty($cartIds)) {
    //         $cartIdPlaceholders = implode(', ', array_map(function ($cartId, $index) {
    //             return ":cart_id_" . $index;
    //         }, $cartIds, array_keys($cartIds)));
    //     }

    //     // dump($shipmentIds);
    //     // dump($shipmentIdPlaceholders);

    //     $params = [];
    //     foreach ($cartIds as $index => $cartId) {
    //         $params[":cart_id_" . $index] = $cartId;
    //     }

    //     $cartConditionString = '';
    //     if ($cartIdPlaceholders) {
    //         $cartConditionString = "AND ci.cart_id IN ({$cartIdPlaceholders})";
    //     }
    //     $stm = str_replace('[cartConditionString]', $cartConditionString, $stm);
    //     // dump(nl2br($stm));
    //     // dump($params);

    //     if ($debug) {
    //         dump(nl2br($stm));
    //         dump($params);
    //     }

    //     $result = $dbm->findAll($stm, $params);
    //     // dump($result);exit;
    //     return $result;
    // }

    /**
     * This is a part of self::getShipmentProductData().
     * Works similarly like the ProductRepository::getProductsData(), and returns a dataset with the same format.
    */
    // private static function getCartProductDataQueryBase(string $locale, bool $getDescription) : string
    // {
    //     App::getContainer()->wireService('WebshopPackage/service/WebshopRequestService');
    //     App::getContainer()->wireService('WebshopPackage/repository/ProductRepository');
    //     App::getContainer()->wireService('WebshopPackage/entity/Cart');
    //     App::getContainer()->wireService('WebshopPackage/entity/ProductPrice');

    //     $stm = "        SELECT 
    //                         -- GROUP_CONCAT(DISTINCT shi.id) as 'unique_key',
    //                         ci.id as 'unique_key',
    //                         ci.quantity as 'quantity',
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
    //                         GROUP_CONCAT(DISTINCT ppa_binder.id) as ppa_binder_id,
    //                         SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT ppa.id), ',', 1) as ppa_binder_product_price_id,
    //                         GROUP_CONCAT(DISTINCT cur_ppa.code) as ppa_currency_code,
    //                         GROUP_CONCAT(DISTINCT ppa.price_type) as ppa_price_type,
    //                         GROUP_CONCAT(DISTINCT ppa.net_price) as ppa_net, 
    //                         GROUP_CONCAT(DISTINCT ppa.gross_price) as ppa_gross, 
    //                         GROUP_CONCAT(DISTINCT ppa.vat) as ppa_vat,
    //                         CONCAT('".($locale == 'en' ? WebshopRequestService::getShowProductLinkBase('en') : WebshopRequestService::getShowProductLinkBase($locale))."' , p." . ($locale == 'en' ? 'slug_en' : 'slug') . ") as product_info_link,
    //                         GROUP_CONCAT(DISTINCT CONCAT(pi.slug , '[main]' , pi.main) SEPARATOR '[separator]') as product_image_slugs
    //                     FROM cart_item ci 
    //                     LEFT JOIN product_price ppa ON ppa.id = ci.product_price_id 
    //                     LEFT JOIN product_price_active ppa_binder ON ppa_binder.product_price_id = ppa.id 
    //                     LEFT JOIN product p ON p.id = ci.product_id
    //                     LEFT JOIN product_category pc ON pc.id = p.product_category_id 
    //                     LEFT JOIN product_price ppl ON (ppl.product_id = p.id AND ppl.price_type = '".ProductPrice::PRICE_TYPE_LIST."') -- List (ppl) 
    //                     LEFT JOIN currency cur_ppl ON cur_ppl.id = ppl.currency_id 
    //                     LEFT JOIN currency cur_ppa ON cur_ppa.id = ppa.currency_id 
    //                     LEFT JOIN product_image pi ON pi.product_id = p.id
    //                     WHERE p.website = '".App::getWebsite()."' 
    //                     AND (pc.id IS NULL OR pc.website = '".App::getWebsite()."')
    //                     [cartConditionString]
    //                     GROUP BY ci.id , p.id
    //                     ORDER BY ci.id ASC
    //     ";

    //     return $stm;
    // }
}
