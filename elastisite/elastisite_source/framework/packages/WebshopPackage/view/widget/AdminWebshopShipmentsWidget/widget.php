<div class="widgetWrapper">
    <?php
    include('framework/packages/WebshopPackage/view/widget/AdminWebshopShipmentsWidget/adminShipmentsControlPanel.php');
    ?>
    <div id="adminShipmentGrid">
    <?php echo $renderedGrid; ?>
    </div>
</div>
<div id="adminShipmentGridAjaxInterfaceContainer">
<?php echo $gridAjaxInterface; ?>
</div>
