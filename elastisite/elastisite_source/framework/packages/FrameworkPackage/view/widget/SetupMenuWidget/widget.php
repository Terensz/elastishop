<?php
    if ($container->isGranted('viewProjectAdminContent')) {
        include('framework/packages/FrameworkPackage/view/widget/SetupMenuWidget/widgetContent.php');
    }
?>