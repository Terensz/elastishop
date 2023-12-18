<div class="sideMenu-container">
    <div class="sideMenu-item">
        <a href="/asc/scaleBuilder/scale/<?php echo $ascScale->getId(); ?>"><?php echo trans('dashboard'); ?></a>
    </div>
</div>
<?php 
include('definitions/pathToBuilder.php');
include($pathToBuilder.'PrimarySubjectBar/html/PrimarySubjectBar.php');
?>
<style>
    .sideMenu-container {
        padding: 10px;
        box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px;
    }
    .sideMenu-item {
        padding: 10px;
    }
</style>