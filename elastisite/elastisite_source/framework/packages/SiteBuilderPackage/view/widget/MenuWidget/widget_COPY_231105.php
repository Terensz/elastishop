
<?php

$routeName = App::getContainer()->getRouting()->getPageRoute()->getName();

if (count($menuItemRoutes) == 0) {
    $menuItemRoutes[] = [
        'routeName' => 'homepage',
        'title' => 'homepage'
    ];
}
?>
<?php if (App::getContainer()->getSession()->get('site_adminViewState') == true): ?>
<?php endif; ?>


<div class="menuWrapper">
    <div class="menuContainer middleOpacity" style="height: 100%; width: 100%; overflow-y:auto;">

<?php if (App::getContainer()->getSession()->get('site_adminViewState') && App::getContainer()->isGranted('viewProjectAdminContent')): ?>
    <div id="menu-cog-container" style="position:relative; z-index: 999999999999999999999;" class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('admin_userAreaMenu'); ?>">
        <div id="menu-cog" class="ajaxCaller skewed menuButton menuButton-first">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#<?php echo ($routeName == 'admin_userAreaMenu') ? '208390' : '9b9b9b'; ?>" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"></path>
                </svg>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($menuItemRoutes as $menuItemRoute): ?>
<?php
// dump($menuItemRoute);
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

<script>
    $('document').ready(function() {
<?php if (App::getContainer()->getSession()->get('site_adminViewState')): ?>
        // $('#stickyMenuStart').popover({
        //     'container': '#menu-container',
        //     'content': 'Default title value if title attribute isnt present. If a function is given, it will be called with its this reference set to the element that the popover is attached to.',
        //     'html': true,
        //     'placement': 'top'
        // });
        // $('#menu-cog').popover('show');
<?php endif; ?>
    });
    $('#menu-cog').on('click', function() {
        // $('#menu-container').after('<div style="width: 100%; background-color: 000;">ALMA</div>');
    });
</script>


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="topnav">
  <a href="#home" class="active">Logo</a>
  <div id="myLinks">
    <a href="#news">News</a>
    <a href="#contact">Contact</a>
    <a href="#about">About</a>
  </div>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div> -->