<div class="widgetWrapper">
<?php  
    // dump($productCategories);exit;
    $webshopRootAdditionalClassStr = '';
    if ($grantedViewProjectAdminContent && App::getContainer()->getSession()->get('site_adminViewState')):
        $webshopRootAdditionalClassStr = '-border sideMenuButton';
        $activeStr = $listNonListables ? '-normal-active' : '-normal';
?>
        <a class="menuLink" href="<?php echo $httpDomain.'/'.$listNonListablesLink; ?>">
            <div class="ajaxCaller sideMenuButton sideMenuButton<?php echo $activeStr; ?> sideMenu-highlightedItem"><?php echo trans('non.listable.products'); ?></div>
        </a>
<?php
    endif;
?>

    <?php  
    // dump($listAll);
    $activeStr = $listAll ? '-normal-active' : '-normal';
    ?>
    <?php 
    // echo $container->getKernelObject('RoutingHelper')->getLink('homepage'); 
    // echo $container->getUrl()->getHttpDomain().($container->getSession()->getLocale() == 'hu' ? '/webaruhaz' : '/webshop');
    ?>
        <a class="menuLink" href="<?php echo $httpDomain.'/'.$listAllLink; ?>">
            <div class="ajaxCaller sideMenuButton sideMenuButton<?php echo $webshopRootAdditionalClassStr; ?><?php echo $activeStr; ?>"><?php echo trans('all.products'); ?></div>
        </a>
    <?php  
    $activeStr = $listDiscounted ? '-special-active' : '-special';
    ?>
        <a class="menuLink" href="<?php echo $httpDomain.'/'.$listDiscountedLink; ?>">
            <div class="ajaxCaller sideMenuButton sideMenuButton-border sideMenuButton<?php echo $activeStr; ?>"><?php echo trans('discounted.products'); ?></div>
        </a>
        <!-- <a class="menuLink" href="http://localhost/elastisite/webroot/webshop">
            <div class="ajaxCaller sideMenuButton-special-2"><?php echo trans('special.offers'); ?></div>
        </a> -->
    <?php foreach ($productCategories as $productCategory): ?>
    <?php 
        // dump($productCategory->getSlug().'/'.$categorySlug);
        $activeStr = $productCategory['slug'] == $categorySlug ? '-normal-active' : '-normal';
    // $route = $container->getKernelObject('RoutingHelper')->searchRoute('webshop/category/'.$productCategory->getName());
    // dump($route->getName());
    // $paramChain = $container->getKernelObject('RoutingHelper')->findParamChain($route->getName());
    // dump($productCategory);
    ?>
        <?php if ($productCategory['productCategory'] == null): ?>
        <a class="menuLink" href="<?php echo $httpDomain.'/'.$productCategory['categoryLink']; ?>">
            <div class="ajaxCaller sideMenuButton sideMenuButton-normal sideMenuButton-border sideMenuButton<?php echo $activeStr; ?>"><?php echo $productCategory['displayedName']; ?></div>
        </a>
            <?php foreach ($productCategories as $childProductCategory): ?>
                <?php //dump($childProductCategory); ?>
                <?php if ($childProductCategory['productCategory'] && $childProductCategory['productCategory']['id'] == $productCategory['id']): ?>
                    <?php 
                        $activeStr = $childProductCategory['slug']  == $categorySlug ? '-normal-active' : '-normal';    
                    ?>
                    <div class="sideMenuButton-submenu-outerContainer">
                        <div class="sideMenuButton-submenu-innerContainer-">
                            <a class="menuLink" href="<?php echo $httpDomain.'/'.$childProductCategory['categoryLink']; ?>">
                                <div class="ajaxCaller sideMenuButton sideMenuButton-normal sideMenuButton-border sideMenuButton<?php echo $activeStr; ?>">» <?php echo $childProductCategory['displayedName']; ?></div>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?>

    <?php endforeach; ?>

    <!-- <div style="padding-top: 20px;"></div> -->

</div>