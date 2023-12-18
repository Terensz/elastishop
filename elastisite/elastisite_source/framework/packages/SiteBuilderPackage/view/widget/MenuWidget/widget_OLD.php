<?php
    if ($container->isGranted('viewProjectAdminContent')) {
        include('framework/packages/SiteBuilderPackage/view/widget/AdminMenuWidget/widget.php');
    }
?>
<?php 

// $mainUrlParam = framework\kernel\utility\BasicUtils::explodeAndGetElement($container->getUrl()->getParamChain(), '/', '1');
$routeName = $container->getRouting()->getPageRoute()->getName();
// dump($container->getRouting()->getPageRoute());exit;

?>
<?php if ($container->getSession()->get('site_adminViewState') == true): ?>
<!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
</svg> -->
<?php endif; ?>
<div class="menuWrapper">
    <div class="menuContainer middleOpacity" style="height: 100%; overflow-y:auto;">

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

<?php 
$activeStr = $routeName == 'homepage' ? ' menuButton-active' : ' menuButton-normal';
?>

        <div class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('homepage'); ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans('homepage'); ?></div>
            </div>
        </div>

<?php 
$activeStr = $routeName == 'contact' ? ' menuButton-active' : ' menuButton-normal';
?>

        <div class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('contact'); ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans('contact'); ?></div>
            </div>
        </div>

<?php 
$routeNameParts = explode('_', $routeName);
$activeStr = $routeNameParts[0] == 'webshop' ? ' menuButton-active' : ' menuButton-normal';
?>

        <div class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('webshop_productList_noFilter'); ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans('test.webshop'); ?></div>
            </div>
        </div>

<?php if ($container->isGranted('viewOnlyUserNotAdminContent') && $container->getUser()->getUserAccount()): ?>
<?php 

$activeStr = $routeName == 'user_handlePersonalData' ? ' menuButton-active' : ' menuButton-normal';
// dump($container->getUser());
// if ($container->getUser()->getUserAccount() && $container->getUser()->getUserAccount()->getPerson()) {
//     echo $container->getUser()->getUserAccount()->getPerson()->getUsername();
// }

?>
        <div class="menuLink" href="<?php echo App::getContainer()->getKernelObject('RoutingHelper')->getLink('user_handlePersonalData'); ?>">
            <div class="ajaxCaller skewed menuButton menuButton-first<?php echo $activeStr; ?>">
                <div class="unskewed"><?php echo trans('logged.in'); ?>: <b><?php echo $container->getUser()->getUserAccount()->getPerson()->getUsername(); ?></b></div>
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