<style>
.activePriceRow {
    background-color: #55c157;
    font-weight: bold;
}
</style>

<!-- <div class="widgetWrapper-info">
<?php echo trans('product.pricing.rules'); ?>
</div> -->

<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('information'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
            <?php echo trans('product.pricing.rules'); ?>
        </span>
    </div>
</div>

<div class="newItem mb-4">
    <button id="AscScaleLister_newScale" onclick="ProductPrice.new(event);" class="btn btn-success"><?php echo trans('create.price'); ?></button>
</div>

<!-- <div id="newProductPrice-button">
    <a class="doNotTriggerHref" href="" onclick="ProductPrice.new(event);"><?php echo trans('create.price'); ?></a>
</div> -->

<div id="newProductPrice-form">
</div>

<?php 
if ($productPriceList && count($productPriceList) > 0):
?>
<div>

    <div class="row">
        <div class="col-3 grid-title-cell grid-title-cell-background">
            <?php echo trans('note'); ?>
        </div>
        <div class="col-2 grid-title-cell grid-title-cell-background">
            <?php echo trans('gross.price'); ?>
        </div>
        <div class="col-1 grid-title-cell grid-title-cell-background">
            <?php echo trans('type'); ?>
        </div>
        <div class="col-1 grid-title-cell grid-title-cell-background">
            <?php echo trans('vat'); ?>
        </div>
        <div class="col-2 grid-title-cell grid-title-cell-background">
            <?php echo trans('activating'); ?>
        </div>
        <div class="col-3 grid-title-cell grid-title-cell-background">
            <?php echo trans('delete'); ?>
        </div>
    </div>
<?php 
// dump($productPriceList);//exit;
    foreach ($productPriceList as $productPrice):
        $active = $productPriceActive && $productPriceActive->getProductPrice()->getId() == $productPrice->getId() ? true : false;
        $activeClassStr = $active ? ' activePriceRow' : '';
        $deletable = !$active ? true : (count($productPriceList) == 1 ? true : false);
        if ($productPrice->getRepository()->isDeletable($productPrice->getId()) == false) {
            $deletable = false;
        }
        if ($productPrice->getPriceType() == 'list') {
            if ($discountPricesCount > 0) {
                $deletable = false;
            }
        }

        // count($productPriceList) == 1

?>
    <div class="row">
        <div class="col-3 grid-body-cell<?php echo $activeClassStr; ?>">
            <?php echo $productPrice->getTitle(); ?>
        </div>
        <div class="col-2 grid-body-cell<?php echo $activeClassStr; ?>">
            <?php echo $productPrice->getGrossPrice(); ?>
        </div>
        <div class="col-1 grid-body-cell<?php echo $activeClassStr; ?>">
            <?php echo trans($productPrice->getPriceType()); ?>
        </div>
        <div class="col-1 grid-body-cell<?php echo $activeClassStr; ?>">
            <?php echo $productPrice->getVat(); ?>
        </div>
        <div class="col-2 grid-body-cell<?php echo $activeClassStr; ?>">
    <?php
        if (!$active):
    ?>
            <a class="doNotTriggerHref" href="" onclick="ProductPrice.activate(event, <?php echo $productId; ?>, <?php echo $productPrice->getId(); ?>);"><?php echo trans('set.as.active') ?></a>
    <?php
        else:
            echo trans('active');
        endif;
    ?>
        </div>
        <div class="col-3 grid-body-cell<?php echo $activeClassStr; ?>">
    <?php
        if ($deletable):
    ?>
                <a class="doNotTriggerHref" href="" onclick="ProductPrice.delete(event, <?php echo $productPrice->getId(); ?>);"><?php echo trans('delete') ?></a>
    <?php
        else:
            echo trans('undeletable');
        endif;
    ?>
        </div>
    </div>
<?php
    endforeach;
?>
    <div class="row" style="border-bottom: 1px solid #c0c0c0;">
    </div>
</div>
<?php
endif;
?>