    <div class="col-xl-<?php echo $bootstrapColUnits; ?> col-lg-6 col-md-6 col-sm-6 widgetRail widgetRail-<?php echo $slabPos; ?>">
        <div onClick="AdminWebshopProductList.edit(event, <?php echo $product->getId(); ?>);" class="blockWrapper blockWrapper-inactive" style="border-radius: 6px;">
    <?php 
            if ($mainImage) {
    ?>
                <div class="webshopProductImage webshopThumbnail" style="
                background-image: url('/webshop/image/thumbnail/<?php echo $mainImage->getSlug(); ?>');">
                </div>
    <?php 
            } else {
    ?>
                <div class="webshopProductImage webshopThumbnail" style="
                background-color: #212121; text-align: center;">
                </div>
    <?php 
            }

    ?>
            <div class="webshop-product-title">
            <?php echo $product->getName(); ?>
            </div>
            <div class="article-content">
            <?php 
            foreach ($errors[$product->getId()] as $errorMessage) {
            ?>
                <div><?php echo $errorMessage; ?></div>
            <?php
            }
            ?>
            </div>
    <?php 
    ?>
        </div>
    </div>