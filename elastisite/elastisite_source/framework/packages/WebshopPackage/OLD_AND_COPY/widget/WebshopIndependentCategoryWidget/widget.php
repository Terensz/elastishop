
<?php  
// dump($products);
?>
<style>
.webshop-secondaryThumbnail {
    /* height: 120px !important;
    max-width: 200px !important; */
    /* max-width: 550px; */
    border-radius: 6px;
    box-shadow: 2px 2px 2px #515151;
              /* inset 2px 3px 5px #676767,
              inset -2px -3px 5px #676767; */
    background-position: center top;
    background-size: cover;
    /* overflow: none; */
    margin-bottom: 5px;
}

.webshopTriggerProductInfo {
    cursor: pointer;
}
</style>

<div class="modal fade" id="productInfoModal" tabindex="-1" role="dialog" aria-labelledby="productInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productInfoModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="productInfoModalBody" class="modal-body"></div>
      <!-- <div class="modal-footer">
        <button id="productInfoModalClose" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo trans('close'); ?></button>
        <button id="productInfoModalConfirm" type="button" class="btn btn-primary"><?php echo trans('confirm'); ?></button>
      </div> -->
    </div>
  </div>
</div>

<?php if ($category): ?>
    <?php if (count($products) > 0): ?>
    <div class="widgetWrapper-off" style="padding: 20px; padding-bottom: 0px; margin-bottom: 20px;">
        <?php foreach ($products as $product): ?>
        <div class="widgetWrapper independentCategory" style="margin-bottom: 10px;">
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-m-5 col-l-4 col-xl-4">
                    <div class="webshopThumbnail-container" style="width: 100%;">
                        <div class="webshopThumbnail-container">
            <?php 
                        $mainImage = $product ? $product->getRepository()->getMainImage($product->getId()) : null;
                        if ($mainImage):
            ?>
                            <div data-id="<?php echo $product->getId(); ?>" class="webshopTriggerProductInfo webshopThumbnail" style="
                            background-image: url('/webshop/image/thumbnail/<?php echo $mainImage->getSlug(); ?>');">
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
                    </div>
                </div>
                <div class="col-xs-12 col-sm-7 col-m-7 col-l-8 col-xl-8">
                    <div class="row webshop-product-title noTextDecoration" style="padding-bottom: 20px;">
                        <?php echo $product->getName(); ?>
                    </div>
                    <div class="article-content">
                        <?php echo html_entity_decode($product->getDescription()); ?>
                    </div>
                </div>
            </div>
<?php 
$secondaryImages = $product->getRepository()->getSecondaryImages($product->getId());
// dump(); 
?>
<?php if (isset($secondaryImages) && $secondaryImages && is_array($secondaryImages) && count($secondaryImages) > 0): ?>  
            <div class="row independentCategory-gallery" style="margin-left: 0px; margin-top: 5px; padding-top: 6px; padding-right: 10px; padding-left: 2px; padding-bottom: 6px; background-color: #c0c0c0; border-radius: 6px;">
                <?php foreach ($secondaryImages as $secondaryImage): ?>
                <div data-id="<?php echo $product->getId(); ?>" data-imageid="<?php echo $secondaryImage->getId(); ?>" class="webshop-secondaryThumbnail webshopTriggerProductInfo" style="
                background-image: url('/webshop/image/thumbnail/<?php echo $secondaryImage->getSlug(); ?>'); width: 160px !important; height: 120px !important; margin-left: 10px; margin-top: 4px;">
                </div>
                <?php endforeach; ?>
            </div>
<?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="widgetWrapper-off" style="padding: 20px;">
        <div class="widgetWrapper" style="margin-bottom: 0px;">
            <?php echo trans('no.displayable.items'); ?>
        </div>
    </div>
    <?php endif; ?>
<?php else: ?>
    <div class="widgetWrapper-off" style="padding: 20px;">
        <div class="widgetWrapper" style="margin-bottom: 0px;">
            <?php echo trans('error.404.title'); ?>
        </div>
    </div>
<?php endif; ?>

<script>
var ProductInfo = {
    show: function(productId, selectedImageId) {
        // console.log('show id: ' + productId);
        $.ajax({
            'type' : 'POST',
            'url' : '/webshop/productInfo/widget',
            'data': {
                'productId': productId,
                'selectedImageId': selectedImageId
            },
            'async': true,
            'success': function(response) {
                ElastiTools.checkResponse(response);
                $('#productInfoModalLabel').html(response.data.title);
                $('#productInfoModalBody').html(response.view);
                $('#productInfoModal').modal('show');
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            },
        });
    }
};

$(document).ready(function() {
    // $('body').off('click', '.webshopTriggerProductInfo');
    // $('body').on('click', '.webshopTriggerProductInfo', function(e) {
    //     console.log('webshopTriggerProductInfo');
    //     e.preventDefault();
    //     ProductInfo.show($(this).attr('data-id'));
    // });
    $('.webshopTriggerProductInfo').off('click');
    $('.webshopTriggerProductInfo').on('click', function(e) {
        console.log('webshopTriggerProductInfo');
        e.preventDefault();
        ProductInfo.show($(this).attr('data-id'), $(this).attr('data-imageid'));
    });
});
</script>