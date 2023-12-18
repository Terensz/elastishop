<div class="widgetWrapper">
<?php 
// dump($menuSystem);
foreach ($menuSystem as $menu):
    if (in_array($routeName, $menu['routeNames'])):
?>
        <div class="sideMenu-title">
            <?php echo trans($menu['groupTitle']); ?>
        </div>
<?php
        foreach ($menu['items'] as $menuItem): 
            $activeStr = $container->getRouting()->getPageRoute()->getName() == $menuItem['routeName'] ? ' sideMenu-active' : '';
            $link = $container->getRoutingHelper()->getLink($menuItem['routeName']);
?>
    <div class="sideMenu-item">
        <a class="ajaxCallerLink<?php echo $activeStr; ?>" href="<?php echo $link; ?>"><?php echo trans($menuItem['title']); ?></a>
    </div>

<?php 
        endforeach;
    endif;
endforeach;
?>
</div>