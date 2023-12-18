<?php
if ($container->isGranted('viewProjectAdminContent')) {
    include('framework/packages/SchedulePackage/view/admin/formScripts.php');
}

if ($events) {
    $widgetJsClass = 'EventWidget';
    foreach ($events as $event) {
        include('framework/packages/SchedulePackage/view/EventWidget/event.php');
    }
}
?>
