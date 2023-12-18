<?php
$adminMenuItems = array(
    array(
        'routeName' => 'homepage',
        'paramChain' => '',
        'title' => 'homepage'
    ),
    array(
        'routeName' => 'admin_index',
        'paramChain' => 'admin',
        'title' => 'admin'
    ),
    // array(
    //     'routeName' => 'elastisite_documentation_index',
    //     'routeNames' => [
    //         'elastisite_documentation_index', 
    //         'elastisite_documentation_how-to-start'
    //     ],
    //     'paramChain' => 'elastisite/documentation',
    //     'title' => 'elastisite.documentation.short'
    // ),
    // array(
    //     'routeName' => 'admin_index',
    //     'paramChain' => 'admin',
    //     'title' => 'admin.users.title'
    // ),
    // array(
    //     'routeName' => 'admin_index',
    //     'paramChain' => 'admin',
    //     'title' => 'other.stuffs'
    // ),
);
?>

<style>
.menuContainerHeight {
    height: 40px;
}
/* .adminMenuLink {
    width: auto;
}

.menuWrapper {
    background-color: #fff;
}

.menuContainer {
    color: #494949;
    background-color: #454545;
    width: 100%;
    border-top: 1px solid #7c7c7c;
    border-bottom: 1px solid #7c7c7c;
}

.menuButton {
    background-color: #101010;
    height: 100%;
    padding: 10px;
    top: 50%;
    float:left;
    border-right: 1px solid #7c7c7c;
    text-align: center;
    color: #fff;
    display: block;
}

.adminMenuButton-active {
    background-color: #75061b;
    padding: 10px;
    float:left;
    border-right: 1px solid #445875;
    text-align: center;
    color: #fff;
    display: block;
}

.menuButton:hover {
    background-color: #ae0c23;
    border-right: 1px solid #7c889a;
    color: #fff;
}

.menuButton-last {
    background-color: #fff;
    padding: 10px;
    float:left;
    text-align: center;
} */

.menu-warning {
    text-align: right;
    padding: 10px;
    color: #d80b0b;
    font-weight: bold;
}
</style>

    <div class="menuWrapper">
        <div class="menuContainer middleOpacity" style="height: 100%; overflow-y:auto;">
<?php
// dump($adminMenuItems);exit;
$pageRoute = $container->getRouting()->getPageRoute()->getName();
foreach ($adminMenuItems as $adminMenuItem) {
    $routeNames = isset($adminMenuItem['routeNames']) ? $adminMenuItem['routeNames'] : [];
    $activeStr = ' menuButton-normal';
    // dump($container->getUrl());
    if ($container->getUrl()->getParamChain() == $adminMenuItem['paramChain'] 
    || $container->getUrl()->getMainRouteRequest() == $adminMenuItem['paramChain']) {
        $activeStr = ' menuButton-active';
    }

    if ($activeStr == ' menuButton-normal' && in_array($pageRoute, $routeNames)) {
        $activeStr = ' menuButton-active';
    }
?>
            <a class="menuLink" href="<?php echo $container->getRoutingHelper()->getLink($adminMenuItem['routeName']); ?>">
                <div class="ajaxCaller menuButton<?php echo $activeStr; ?>">
                    <?php echo trans($adminMenuItem['title']); ?>
                </div>
            </a>
<?php
}
?>
            <!-- <div class="menu-warning shadowedText"><?php echo trans('logged.as.administrator'); ?></div> -->
        </div>
    </div>
