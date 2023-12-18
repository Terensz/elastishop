<?php 
// dump($case);
?>

<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('error'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
            <b><?php echo trans('invalid.shipment'); ?> <?php if (isset($case)) { echo '('.$case.')'; } ?></b><br><br>
            <?php echo trans('invalid.shipment.description'); ?>
            <div style="padding-top: 20px;">
                <a class="ajaxCallerLink" href="<?php echo $webshopBaseLink; ?>"><?php echo trans('return.to.webshop'); ?></a>
            </div>
        </span>
    </div>
</div>