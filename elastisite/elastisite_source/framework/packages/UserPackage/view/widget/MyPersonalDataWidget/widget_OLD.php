<?php 

// dump($container->getUser());

?>

<div class="widgetWrapper-noPadding">
    <div class="widgetHeader widgetHeader-color">
        <div class="widgetHeader-titleText"><?php echo trans('my.stored.personal.data'); ?></div>
    </div>
    <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
        <div class="article-content">
            <!-- <div class="row">
                <div class="col-sm-4">
                <?php echo trans('id'); ?>
                </div>
                <div class="col-sm-8">
                <b><?php echo $container->getUser()->getId(); ?></b>
                </div>
            </div> -->
            <div class="row">
                <div class="col-sm-4">
                <?php echo trans('name'); ?>
                </div>
                <div class="col-sm-8">
                <b><?php echo $container->getUser()->getName(); ?></b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                <?php echo trans('username'); ?>
                </div>
                <div class="col-sm-8">
                <b><?php echo $container->getUser()->getUsername(); ?></b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                <?php echo trans('email'); ?>
                </div>
                <div class="col-sm-8">
                <b><?php echo $container->getUser()->getEmail(); ?></b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                <?php echo trans('mobile'); ?>
                </div>
                <div class="col-sm-8">
                <b><?php echo $container->getUser()->getMobile(); ?></b>
                </div>
            </div>
        </div>
<?php 
if (count($addresses) > 0) {
?>
        <div style="padding-top:20px;padding-bottom: 10px;">
        <b><?php echo trans('my.addresses'); ?></b>
        </div>
<?php
// dump($addresses);
    foreach ($addresses as $address) {
?>
        <div class="rowSeparator"></div>
        <div>
        <?php echo trans($address->getCountry()->getTranslationReference().'.country'); ?>, <?php echo $address->getZipCode(); ?> <?php echo $address->getCity(); ?>, <?php echo $address->getStreet(); ?> <?php echo $address->getStreetSuffix(); ?> <?php echo $address->getHouseNumber(); ?> <?php echo $address->getStaircase(); ?> <?php echo $address->getFloor(); ?> <?php echo $address->getDoor(); ?>
        </div>
<?php 
    }
}
?>
        <div class="articleFooter"></div>
    </div>
</div>


<div class="widgetWrapper-noPadding">
    <div class="widgetHeader widgetHeader-color">
        <div class="widgetHeader-titleText"><?php echo trans('how.to.remove.my.personal.data'); ?></div>
    </div>
    <div class="widgetWrapper-textContainer widgetWrapper-textContainer-bottomMargin">
        <div class="article-content">

            <div class="sideMenu-item">
                <a class="ajaxCallerLink" href="<?php echo $container->getRoutingHelper()->getLink('user_removePersonalData'); ?>"><?php echo trans('method.of.removing.my.personal.data'); ?></a>
            </div>

        </div>
        <div class="articleFooter"></div>
    </div>
</div>