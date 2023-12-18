<?php

use framework\component\helper\StringHelper;

$container = App::getContainer();
?>    
    
    <div class="col-xl-<?php echo $bootstrapColUnits; ?> col-lg-6 col-md-6 col-sm-6 widgetRail widgetRail-<?php echo $slabPos; ?> bs-conf-<?php echo $bootstrapColUnits; ?>-<?php echo $bootstrapColUnits; ?>">
        <div class="blockWrapper">
    <?php  
    $showProductLink = $container->getKernelObject('RoutingHelper')->getLink('webshop_showProduct', ['productSlug' => $product->getSlug()]);
    ?>
            <a href="<?php echo $showProductLink; ?>">
                <div class="webshopThumbnail-container">
    <?php 
                if ($mainImage):
    ?>
                    <div data-id="<?php echo $product->getId(); ?>" class="webshopTriggerProductInfo webshopThumbnail" style="
                    background-image: url('/webshop/image/thumbnail/<?php echo $mainImage->getSlug(); ?>');">
                    <?php if ($discount): ?>
                        <div style="text-align: right;" class="toggled-special-2 webshop-productList-discountBar">-<?php echo $discountPercent; ?> % </div>
                    <?php endif ?>
                    </div>
    <?php 
                else:
    ?>
                    <div data-id="<?php echo $product->getId(); ?>" class="webshopTriggerProductInfo webshopThumbnail" style="
                    background-color: #212121; text-align: center;">
                    </div>
    <?php 
                endif;
    ?>
                </div>
                
                <div data-id="<?php echo $product->getId(); ?>" class="webshopTriggerProductInfo webshop-product-title">
                    <?php echo $product->getName(); ?>
                </div>
            </a>
    <?php if (!$productCategory): ?>
    <?php   
        // Originally this list only displayed category, when the search was mixed, but I rather changed this operating, because this feature seems contraproductive for me. 
    ?>
    <?php endif; ?>

            <!-- <div style="height: 10px;">&nbsp;</div> -->

            <div class="webshop-category-title">
                <?php echo $product->getProductCategory() ? $product->getProductCategory()->getName() : ''; ?>
            </div>

            <?php if (100 == 2000): ?>
            <div>
                <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/star-fill.svg">
                <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/star.svg">
                <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/star.svg">
                <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/star.svg">
                <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/Bootstrap-icons/star.svg">
            </div>
            <?php endif; ?>

            <div class="article-content addToCartButtonContainer" data-addtocartid="addtocart-<?php echo $product->getProductPriceActive()->getId(); ?>">
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
            <div class="article-content">
            <b><?php echo $priceString; ?></b>
            </div>
            <div class="article-content">
            <?php echo StringHelper::cutLongString(strip_tags(html_entity_decode($product->getDescription())), 40); ?>
            </div>
<?php 
?>
        </div>
    </div>