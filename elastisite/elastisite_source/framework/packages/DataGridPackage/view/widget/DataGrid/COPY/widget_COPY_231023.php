<!-- DataGrid!!! -->

<!-- <div class="widgetWrapper"></div> -->

<style>
    .multiselect-input {
        width: 100%;
    }
    .select2-selection__choice__display {
        color: #000;
    }
</style>

<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

<?php 

// dump($orderByField);
// dump($orderByDirection);
// dump($dataGrid);
// dump($posts);
// dump($pager);
?>

<?php if ($wrapWithWidgetWrapper): ?>
<div class="widgetWrapper">
<?php endif; ?>

<?php if ($preloadRenderedHtml): ?>
<?php
echo $preloadRenderedHtml;
?>
<?php endif; ?>

<?php 
include('framework/packages/DataGridPackage/view/DataGrid/dataGridControlPanel.php');
?>

    <div id="dataGrid-<?php echo $dataGridId; ?>">
<?php 
include('framework/packages/DataGridPackage/view/DataGrid/dataGridParts.php');
?>
    </div>
<?php 
include('framework/packages/DataGridPackage/view/DataGrid/scripts.php');
?>

<?php if ($wrapWithWidgetWrapper): ?>
</div>
<?php endif; ?>