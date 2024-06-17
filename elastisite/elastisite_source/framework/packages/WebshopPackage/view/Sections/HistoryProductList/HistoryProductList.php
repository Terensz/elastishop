<?php

use framework\component\helper\StringHelper;

?>
<?php if (count($last10) > 0): ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0"><?php echo trans('last.viewed.products'); ?></h6>
        </div>
    </div>
    <div class="card-footer">
        <?php foreach ($last10 as $productVisitHistoryDataSet): ?>
        <div class="tinyImageList-container">
            <a href="<?php echo '/'.$localizedProductInfoLinkBase.$productVisitHistoryDataSet['product']['slug']; ?>" onclick="Webshop.showProductDetailsModalInit(event, '<?php echo $productVisitHistoryDataSet['product']['id']; ?>');" class="pc-link">
                <div class="tinyImageList-image" style="background-image: url('<?php echo $productVisitHistoryDataSet['product']['mainProductImageLink']; ?>');"></div>
                <div class="tinyImageList-label linkText">
                    <?php echo StringHelper::cutLongString($productVisitHistoryDataSet['product']['name'], 7); ?>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>