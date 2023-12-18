<div class="list-item-container">
    <div class="list-item-thumbnail">
        <img src="<?php echo $container->getUrl()->getHttpDomain(); ?>/image/background/thumbnail/<?php echo $background->getTheme().'.'.$background->getExtension(); ?>">
    </div>
    <div class="row">
        <div class="col-8">
            <div class="list-item-attribute">
                <?php echo $background->getEngine() == 'SlidingStripes' ? 'Beúszó csíkok' : 'Egyszerű'; ?>
            </div>
            <div class="list-item-attribute">
                <b>
                    <?php echo $background->getTitle(); ?>
                </b>
            </div>
        </div>
        <div class="col-4">
<?php
    if (in_array($background->getTheme(), $bindedBgs)) {
?>
            <?php echo trans('binded.to.page'); ?>
<?php
    } else {
?>
            <a href="" class="triggerModal" onclick="BackgroundList.delete(event, '<?php echo $background->getId(); ?>');"><?php echo trans('delete'); ?></a>
<?php
    }
?>
        </div>
    </div>
</div>
