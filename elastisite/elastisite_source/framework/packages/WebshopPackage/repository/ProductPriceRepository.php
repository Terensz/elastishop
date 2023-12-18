<?php
namespace framework\packages\WebshopPackage\repository;

use App;
use framework\component\parent\DbRepository;

class ProductPriceRepository extends DbRepository
{
    public function isDeletable($id)
    {
        // $productPrice = $this->find($id);
        // $product = $productPrice->getProduct();

        $dbm = $this->getDbManager();

        $stm = "SELECT count(si.id) as si_id_count
        FROM shipment_item si WHERE si.product_price_id = :id ";
        $result = $dbm->findOne($stm, ['id' => $id])['si_id_count'];
        
        if ($result > 0) {
            return false;
        }

        $stm = "SELECT count(ci.id) as ci_id_count
        FROM cart_item ci WHERE ci.product_price_id = :id ";
        $result = $dbm->findOne($stm, ['id' => $id])['ci_id_count'];
        
        if ($result > 0) {
            return false;
        }

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

    public static function getAssignedVatsOfProduct($productId)
    {
        $stm = "SELECT
            GROUP_CONCAT(DISTINCT pp.vat SEPARATOR ',') as 'pp_vat'
        FROM product_price pp 
        WHERE pp.product_id = :product_id 
        GROUP BY pp.product_id
        ";
        $dbm = App::getContainer()->getDbManager();

        return $dbm->findOne($stm, ['product_id' => $productId]);
    }    

    public function remove($id)
    {
        if (!$this->isDeletable($id)) {
            return false;
        }
        $productPrice = $this->find($id);
        $productPriceActive = $productPrice->getProduct()->getProductPriceActive();
        if ($productPriceActive) {
            $productPriceActive->getRepository()->remove($productPriceActive->getId());
        }

        return parent::remove($id);
    }

    public function getPriceData($id)
    {
        $stm = "SELECT p.id
            , ppa.id as 'active_price_id'
            , ppa.net_price as 'active_net_price'
            , ppa.vat as 'active_vat'
            , ppl.id as 'list_price_id'
            , ppl.net_price as 'list_net_price'
            , ppl.vat as 'list_vat'
            , pp.id as 'requested_price_id'
            , pp.net_price as 'requested_net_price'
            , pp.vat as 'requested_vat'
        FROM product_price pp 
        INNER JOIN product p ON p.id = pp.product_id
        LEFT JOIN product_category pc ON pc.id = p.product_category_id
        INNER JOIN product_price_active ppact ON ppact.product_id = p.id
        INNER JOIN product_price ppa ON ppa.id = ppact.product_price_id
        INNER JOIN product_price ppl ON ppl.product_id = p.id AND ppl.price_type = 'list'
        WHERE pp.id = :productPriceId ";
        $dbm = $this->getDbManager();

        return $dbm->findOne($stm, ['productPriceId' => $id]);
    }

    public function getActivePriceId($productId)
    {
        $stm = "SELECT app.id as app_id
        FROM product p 
        INNER JOIN product_price_active ppa ON ppa.product_id = p.id
        INNER JOIN product_price app ON app.id = ppa.product_price_id
        WHERE p.id = :productId ";
        $dbm = $this->getDbManager();
        $result = $dbm->findOne($stm, ['productId' => $productId]);

        return $result ? $result['app_id'] : null;
    }

    // public function getActivePriceData($productId)
    // {
    //     $stm = "SELECT p.id
    //         , ppd.net_price as 'discount_net_price'
    //         , ppd.vat as 'discount_vat'
    //         , ppl.net_price as 'list_net_price'
    //         , ppl.vat as 'list_vat'
    //     FROM product p 
    //     LEFT JOIN product_category pc ON pc.id = p.product_category_id
    //     INNER JOIN product_price_active ppa ON ppa.product_id = p.id
    //     INNER JOIN product_price ppd ON ppd.id = ppa.product_price_id
    //     INNER JOIN product_price ppl ON ppl.product_id = p.id AND ppl.price_type = 'list'
    //     WHERE p.id = :productId ";
    //     $dbm = $this->getDbManager();

    //     return $dbm->findOne($stm, ['productId' => $productId]);
    // }

    public function getGrossListPrice($productId)
    {
        $stm = "SELECT p.id
            , ppl.gross_price as 'list_gross_price'
            -- , ppl.vat as 'list_vat'
        FROM product p 
        INNER JOIN product_price ppl ON ppl.product_id = p.id AND ppl.price_type = 'list'
        WHERE p.id = :product_id ";

        $dbm = $this->getDbManager();
        $found = $dbm->findOne($stm, ['product_id' => $productId]);
        if ($found) {
            return $found['list_gross_price'];
        } else {
            return null;
        }
    }

    // public function getListPrice_NET($productId)
    // {
    //     $stm = "SELECT p.id
    //         , ppl.net_price as 'list_net_price'
    //         , ppl.vat as 'list_vat'
    //     FROM product p 
    //     INNER JOIN product_price ppl ON ppl.product_id = p.id AND ppl.price_type = 'list'
    //     WHERE p.id = :productId ";

    //     $dbm = $this->getDbManager();
    //     $found = $dbm->findOne($stm, ['productId' => $productId]);
    //     if ($found) {
    //         return $found['list_net_price'] * (1 + ($found['list_vat'] / 100));
    //     } else {
    //         return null;
    //     }
    // }
}
