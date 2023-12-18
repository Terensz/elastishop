<style>
    .webshop-productInfo-container {
        width: 100%;
    }
    .webshop-productInfo-separator {
        padding-top: 12px;
    }
    .webshop-productInfo-price {
        width: 100%;
        color: #393939;
        margin: 6px;
        font-size: 24px;
        /* padding-top: 12px; */
    }
    .webshop-productInfo-description {
        background-color: #f9f9f9;
        width: 100%;
        color: #393939;
        padding: 10px;
        border: 1px solid #c0c0c0;
        /* padding-top: 12px; */
    }
    .webshop-productInfo-addToCart {
        padding-top: 6px;
    }
</style>

<div class="widgetWrapper">
    <div class="webshop-productInfo-container" >
        <?php if ($showProductPage): ?>
            <h1><?php echo $product->getName(); ?></h1>
        <?php endif; ?>
        <?php 
        // ================================================
        // controllerMethod: webshopProductInfoWidgetAction
        // ================================================
        // dump($product);
        // include('framework/packages/WebshopPackage/view/widget/WebshopProductInfoWidget/slideShow.php');
        include('framework/packages/WebshopPackage/view/widget/WebshopProductWidget/slideShow.php');
        // dump($product);
        // $priceData = $webshopService->getPriceData($product->getId());
        // $priceData = $webshopFinanceService->getActivePriceData($product->getId());
        $discount = $product->getProductPriceActive()->getProductPrice()->getPriceType() == 'discount' ? true : false;
        if ($discount) {
            $discountPercent = $priceData['discount_percent'];
            if ($discountPercent <= 0) {
                $discount = false;
            }
        }

        $currencyString = ' '.$defaultCurrency;
        if ($discount) {
            $priceString = '<span class="webshop-old-price">'.$priceData['list_gross_price'].$currencyString.'</span> <span class="webshop-new-price">'.$priceData['discount_gross_price'].$currencyString.'</span>';
        } else {
            $priceString = $priceData['list_gross_price'].$currencyString;
        }

        ?>

        <div class="webshop-productInfo-separator"></div>

        <div class="webshop-productInfo-price">
            <?php 
            // echo '<i>'.trans('price').':</i> ';
            echo '<b>'.$priceString.'</b>'; 
            ?>
        </div>

        <div class="webshop-productInfo-description">
            <?php echo html_entity_decode($product->getDescription()); ?>
        </div>

        <div class="webshop-productInfo-addToCart addToCartButtonContainer" data-addtocartid="addtocart-<?php echo $product->getProductPriceActive()->getId(); ?>">
        <?php 
            if (in_array($product->getProductPriceActive()->getId(), $cartOfferIds)):
                echo '<i>'.trans('already.at.cart').'</i>';
            else:
        ?>
            <a href="" onClick="ProductList.addToCart(event, <?php echo $product->getProductPriceActive()->getId(); ?>);"><?php echo trans('put.to.cart'); ?></a>
        <?php 
            endif;
        ?>
        </div>

    </div>
</div>

<?php
include('framework/packages/WebshopPackage/view/widget/WebshopProductListWidget/widgetJS.php'); 
?>