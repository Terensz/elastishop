<?php 
/**
  * @var $isWebshopTestMode
  *
  */

?>

<?php if ($isWebshopTestMode): ?>
<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <h6 class="mb-0 text-white"><?php echo trans('warning'); ?></h6>
        </div>
    </div>
    <div class="card-body">
        <span>
            <?php echo trans('test.webshop.warning'); ?>
        </span>
    </div>
</div>
<?php endif; ?>
