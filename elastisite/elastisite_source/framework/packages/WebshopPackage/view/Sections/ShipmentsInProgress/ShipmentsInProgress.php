<?php

use framework\packages\UserPackage\entity\User;

?>
<?php if (App::getContainer()->getUser()->getType() == User::TYPE_ADMINISTRATOR): ?>
<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <?php echo trans('information'); ?>
    </div>
    <div class="card-body">
        <div class="">
            <?php echo trans('administrators.unfinished.orders.info'); ?>
        </div>
        <a class="" 
            href="/logout"><?php echo trans('logout'); ?>
        </a>
    </div>
</div>
<?php else: ?>
    <?php  
    // dump($packDataSet);    
    ?>
    <?php if (count($packDataCollection) > 0): ?>
    <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4><?php echo trans('unfinished.orders'); ?> (<?php echo (string)count($packDataCollection); ?>)</h4>
        </div>
    </div>
    <div class="unfinishedOrders-button-show">
        <div class="mb-4">
            <button type="button" onclick="UnfinishedOrders.show(event);" class="btn btn-primary"><?php echo trans('show'); ?></button>
        </div>
    </div>
    <div class="unfinishedOrders-button-hide" style="display: none;">
        <div class="mb-4">
            <button type="button" onclick="UnfinishedOrders.hide(event);" class="btn btn-primary"><?php echo trans('hide'); ?></button>
        </div>
    </div>
    <div class="unfinishedOrders-container" style="display: none;">
    <?php 
    // dump($packDataSet);
    // dump($packDataSet);
    // permittedUserType
    // permittedForCurrentUser
    // if ()
    $additionalShipmentCardFooter = 'framework/packages/WebshopPackage/view/Sections/ShipmentsInProgress/AdditionalShipmentCardFooter.php';
    include('framework/packages/WebshopPackage/view/Common/ShipmentList/ShipmentList.php');
    ?>
    </div>
<script>
    var UnfinishedOrders = {
        show: function() {
            $('.unfinishedOrders-container').show();
            $('.unfinishedOrders-button-show').hide();
            $('.unfinishedOrders-button-hide').show();
        },
        hide: function() {
            $('.unfinishedOrders-container').hide();
            $('.unfinishedOrders-button-show').show();
            $('.unfinishedOrders-button-hide').hide();
        }
    };
</script>
    <?php endif; ?>
<?php endif; ?>