<?php
    if ($container->isGranted('viewProjectAdminContent')) {
        include('framework/packages/FrameworkPackage/view/widget/AdminIndexWidget/widgetContent.php');
    }
?>