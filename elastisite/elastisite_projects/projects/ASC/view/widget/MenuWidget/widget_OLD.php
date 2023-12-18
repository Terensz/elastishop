
<?php

use framework\packages\UserPackage\service\Permission;

$menuItemRoutes = [
    [
        'routeName' => 'homepage',
        'title' => 'homepage',
        'permission' => 'viewGuestContent'
    ],
    [
        'routeName' => 'loginOrRegister',
        'title' => 'login',
        'permission' => 'viewLoggedOutContent'
    ],
    [
        'routeName' => 'asc_scaleLister',
        'routePath' => 'asc/scaleLister',
        'title' => 'admin.scales',
        'permission' => 'viewUserContent'
    ],
    [
        'routeName' => 'forum',
        'routePath' => 'forum',
        'title' => 'forum',
        'permission' => 'viewUserContent'
    ]
];
$routeName = App::getContainer()->getRouting()->getPageRoute()->getName();

if (count($menuItemRoutes) == 0) {
    $menuItemRoutes[] = [
        'routeName' => 'homepage',
        'title' => 'homepage',
        'permission' => 'viewGuestContent'
    ];
}
?>
<?php if (App::getContainer()->getSession()->get('site_adminViewState') == true): ?>
<?php endif; ?>

<div class="menuWrapper">
    <div class="menuContainer middleOpacity" style="height: 100%; width: 100%; overflow-y:auto;">

<?php if (App::getContainer()->getSession()->get('site_adminViewState') && App::getContainer()->isGranted('viewProjectAdminContent')): ?>
    <div id="menu-cog-container" style="" class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('admin_userAreaMenu'); ?>">
        <div id="menu-cog" class="ajaxCaller skewed menuButton menuButton-first">

        </div>
    </div>
<?php endif; ?>

<?php foreach ($menuItemRoutes as $menuItemRoute): ?>
<?php
if (!Permission::check($menuItemRoute['permission'])) {
    continue;
}

$url = App::getContainer()->getUrl();
if ($url->getPageRoute()->getName() == 'staff_stats_manage_staffMember') {
    continue;
}

$routeFromLink = null;
$activeStr = ' menuButton-normal';
if (!empty($menuItemRoute['routeName'])) {
    $routeFromLink = App::getContainer()->getKernelObject('RoutingHelper')->getLink($menuItemRoute['routeName']);
    $routeNameParts = explode('_', $routeName);
    if ($menuItemRoute['routeName'] == $routeName) {
        $activeStr = ' menuButton-active';
    }
    
    $webshopStrPos = false;
    if ($menuItemRoute['routeName']) {
        $webshopStrPos = strpos($menuItemRoute['routeName'], 'webshop');
    }
    if ($webshopStrPos === 0 && $routeNameParts[0] == 'webshop') {
        $activeStr = ' menuButton-active';
    }
}

?>

    <?php if ($routeFromLink): ?>
        <div class="menuLink" href="<?php echo $routeFromLink; ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans($menuItemRoute['title']); ?></div>
            </div>
        </div>
    <?php else: ?>
    <?php
    $routePath = isset($menuItemRoute['routePath']) ? $menuItemRoute['routePath'] : null;
    if ($routePath && '/'.App::getContainer()->getUrl()->getParamChain() == $routePath) {
        $activeStr = ' menuButton-active';
    }
    ?>
        <?php if ($routePath): ?>
        <div class="menuLink" href="<?php echo $routePath; ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans($menuItemRoute['title']); ?></div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>

<?php endforeach; ?>

<?php if (App::getContainer()->isGranted('viewOnlyUserNotAdminContent') && App::getContainer()->getUser()->getUserAccount()): ?>
<?php 
$activeStr = $routeName == 'user_handlePersonalData' ? ' menuButton-active' : ' menuButton-normal';
?>
        <div class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('user_handlePersonalData'); ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans('logged.in'); ?>: <b><?php echo App::getContainer()->getUser()->getUserAccount()->getPerson()->getUsername(); ?></b></div>
            </div>
        </div>


<?php endif; ?>
    </div>
</div>