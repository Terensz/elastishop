<?php if (count($scaleData) > 0): ?>
<div class="card-pack">
    <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4><?php echo trans('own.inactive.admin.scale.list'); ?></h4>
        </div>
    </div>
    <?php 
    $cardItemStatus = 'Inactive';
    include('scaleList.php');
    ?>
</div>
<?php endif; ?>