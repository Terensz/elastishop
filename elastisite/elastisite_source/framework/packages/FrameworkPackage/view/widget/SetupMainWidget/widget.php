<?php
    if ($container->isGranted('viewProjectAdminContent')):
        include('framework/packages/FrameworkPackage/view/widget/SetupMainWidget/widgetContent.php');
    elseif (App::getContainer()->getSession()->get('userId') > 0):
?>
    <a class="ajaxCallerLink" href="/logout"><?php echo trans('logout'); ?></a>
<?php
    endif;
?>