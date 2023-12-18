<div class="widgetWrapper">
    <?php
    include('framework/packages/WebshopPackage/view/widget/AdminWebshopProductCategoriesWidget/adminProductCategoriesControlPanel.php');
    ?>
    <div id="adminProductCategoryGrid">
    <?php echo $renderedGrid; ?>
    </div>
</div>
<div id="adminProductCategoryGridAjaxInterfaceContainer">
<?php echo $gridAjaxInterface; ?>
</div>