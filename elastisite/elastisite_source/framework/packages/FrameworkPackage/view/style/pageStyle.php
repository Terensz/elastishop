<?php 
        // echo 'bgcolor: ' . $backgroundColor;exit; 
        // $isAdminPage = $container->getUrl()->getMainRouteRequest() == 'admin';
        // // dump($isAdminPage);exit;
        // $sheetWidthPercent = $container->getSkinData('sheetWidthPercent');
        // if ($isAdminPage) {
        //     if ($container->getSkinData('adminSheetWidthPercent')) {
        //         $sheetWidthPercent = $container->getSkinData('adminSheetWidthPercent');
        //     }
        // }

        $sheetHorizontalMargin = $container->getSkinData('sheetHorizontalMargin');
        // dump($sheetHorizontalMargin);exit;
?>
<script>
    console.log('Sheet.php loaded');
</script>
<style>
body {
    /* background-color: #e4e4e4; dcb063 alma */
    background-color: #<?php echo trim($container->getSkinData('backgroundColor'), '#'); ?>;
}

/* #sheetContainer {
    position: absolute;
    margin-top: <?php echo $container->getSkinData('sheetTopPadding'); ?>px;
    <?php if ($sheetHorizontalMargin === 0 || $sheetHorizontalMargin): ?>
    margin-left: <?php echo $sheetHorizontalMargin; ?>px;
    margin-right: <?php echo $sheetHorizontalMargin; ?>px;
    <?php else: ?>
    margin-left: auto;
    margin-right: auto;
    <?php endif; ?>
    left: 0;
    right: 0;
    overflow:hidden;
} */
/* #sheetContainer_OLD {
    border: auto;
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8nwYAAmoBZ0eMiB8AAAAASUVORK5CYII=');
    box-shadow: 0 2px 4px #4e4e4e;
    margin: 0 auto;
} */
/* #sheetContainer {
    border: auto;
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8nwYAAmoBZ0eMiB8AAAAASUVORK5CYII=');
    box-shadow: 0 2px 4px #4e4e4e;
    margin: 0 auto;
    position: relative;
} */
/* .sheetWidth {
    width: <?php echo App::getContainer()->getPageProperty('sheetWidthPercent'); ?>;
    max-width: <?php echo App::getContainer()->getPageProperty('sheetMaxWidth'); ?>;
} */
/* .sheetWidth {
    width: 90%;
} */
/* .sheetWidth {
    <?php if (!$sheetHorizontalMargin && $sheetHorizontalMargin !== 0): ?>
    width: <?php echo $container->getPageProperty('sheetWidthPercent'); ?>%;
    <?php endif; ?>
    <?php if (!empty($container->getSkinData('sheetMaxWidth')) && !$container->getPageProperty('isAdminPage')): ?>
    max-width: <?php echo $container->getSkinData('sheetMaxWidth'); ?>px;
    <?php endif; ?>
} */

@media only screen and (max-width: 1180px) {
    /* .sheetWidth {
        width: 90% !important;
    } */
}

@media only screen and (max-width: 999px) {
    /* .sheetWidth {
        width: 94% !important;
    } */
}

</style>
