<!-- <div id="newProductImage-button">
    <a class="doNotTriggerHref" href="" onclick="ProductImage.new(event);"><?php echo trans('upload.image') ?></a>
</div> -->

<div id="newProductImage-form">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="custom-file mt-3 mb-3">
            <input type="file" onchange="ProductImage.upload(event);" 
                class="custom-file-input" id="WebshopPackage_uploadProductImage_file" name="WebshopPackage_uploadProductImage_file">
            <label class="custom-file-label" for="WebshopPackage_uploadProductImage_file"><?php echo trans('upload.image'); ?></label>
        </div>
    </form>
</div>


<div class="card">
<?php 
foreach ($productImages as $productImage):
?>
    <!-- <div class="card-footer">
    </div> -->


    <div class="card-footer">
        <div class="row">
            <div class="col-4">
                <div class="card-image-background" style="width: 180px; height: 120px; margin-left: 0px; background-image: url('<?php echo $container->getUrl()->getHttpDomain(); ?>/webshop/image/thumbnail/<?php echo $productImage->getSlug(); ?>');" class="list-item-thumbnail webshopThumbnail">
                </div>
            </div>
            <div class="col-4" style="text-align: center;">
    <?php
        if ($productImage->getMain() == 0) {
    ?>
                <a class="doNotTriggerHref" href="" onclick="ProductImage.setAsMain(event, <?php echo $productImage->getProduct()->getId(); ?>, <?php echo $productImage->getId(); ?>);"><?php echo trans('set.as.main') ?></a>
    <?php
    } else {
                echo trans('main');
    }
    ?>
            </div>
            <div class="col-4" style="text-align: center;">
    <?php
        if ($productImageRepo->isDeletable($productImage->getId())) {
    ?>
                <a class="doNotTriggerHref" href="" onclick="ProductImage.delete(event, <?php echo $productImage->getId(); ?>);"><?php echo trans('delete') ?></a>
    <?php
    } else {
                echo trans('undeletable');
    }
    ?>
                </div>
        </div>
    </div>
<?php 
endforeach;
?>
</div>