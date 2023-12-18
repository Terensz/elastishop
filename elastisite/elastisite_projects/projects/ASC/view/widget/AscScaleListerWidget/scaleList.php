<?php 
// dump($ascSampleHeaders);

use framework\packages\UXPackage\service\ViewTools;

include('definitions/pathToBuilder.php');
// dump($ascScaleList);
App::getContainer()->setService('projects/ASC/repository/AscScaleRepository');
$ascScaleRepository = App::getContainer()->getService('AscScaleRepository');
if (!isset($cardItemStatus)) {
    $cardItemStatus = 'Active';
}
?>


<div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-3 g-4">
<?php foreach ($scaleData as $scaleDataRow): ?>
<?php
    $ascScale = $scaleDataRow['ascScale'];
// dump($entry);
// dump($ascSampleHeader);

    // $ascScaleHeader = [
    //     'id' => $ascScale->getId(),
    //     'title' => $entryHead->findTitle(),
    //     'description' => $entryHead->findDescription(),
    //     'subjectCategory' => $entryHead->getSubjectCategory(),
    // ];
    include('ScaleCard'.($cardItemStatus == 'Inactive' ? 'Inactive' : '').'.php');
?>
<?php endforeach; ?>

</div>