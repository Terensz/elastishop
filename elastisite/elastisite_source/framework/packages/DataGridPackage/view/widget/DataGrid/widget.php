<?php

// use framework\packages\UXPackage\service\ViewTools;
// use projects\ASC\service\AscTechService;

// App::getContainer()->wireService('UXPackage/service/ViewTools');
// App::getContainer()->wireService('projects/ASC/service/AscTechService');

?>

<!-- DataGrid!!! -->

<!-- <div class="widgetWrapper"></div> -->

<style>
    /* .multiselect-input {
        width: 100% !important;
        height: auto !important;
    } */
    .select2-selection__choice__display {
        color: #000;
    }
    /* .DataGrid-row {
        cursor: pointer;
    } */
</style>

<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

<?php 
include('framework/packages/DataGridPackage/view/widget/DataGrid/dataGridControlPanel.php');
?>

<div id="dataGrid-<?php echo $tableData['data']['dataGridId']; ?>">
<?php 
include('framework/packages/DataGridPackage/view/widget/DataGrid/widgetFlexibleContent.php');
?>
</div>

<?php 
$dataGridId = $tableData['data']['dataGridId'];
$listActionUrl = $tableData['urls']['listActionUrl'];
$deleteActionUrl = $tableData['urls']['deleteActionUrl'];
$editActionUrl = $tableData['urls']['editActionUrl'];
$javaScriptOnDeleteConfirmed = $tableData['configuration']['javaScriptOnDeleteConfirmed'];
include('framework/packages/DataGridPackage/view/widget/DataGrid/scripts.php');
?>