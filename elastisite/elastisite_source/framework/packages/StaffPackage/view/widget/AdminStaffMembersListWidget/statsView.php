<?php
$currentPath = __DIR__;
$parentPath = dirname($currentPath);
$pathToStaffMemberStatsDir = $parentPath . '/../StaffMemberStats/';
$chartInstanceId = 1;
include($pathToStaffMemberStatsDir.'ChartInstance.php');
?>

<script>
    $('document').ready(function() {
        StaffMemberStatChart_1.draw();
    });
</script>