<?php 
// dump($ascSampleHeaders);

use framework\packages\UXPackage\service\ViewTools;

include('definitions/pathToBuilder.php');
// dump($ascScaleList);
App::getContainer()->setService('projects/ASC/repository/AscScaleRepository');
$ascScaleRepository = App::getContainer()->getService('AscScaleRepository');
?>


<!-- <div class="row row-cols-1 row-cols-sm-3 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4"> -->
<?php foreach ($ascScaleList as $ascScale): ?>
<?php
    $entryHead = $ascScale->getAscEntryHead();
    $entry = $entryHead->getAscEntry();
// dump($entry);
// dump($ascSampleHeader);

    // $ascScaleHeader = [
    //     'id' => $ascScale->getId(),
    //     'title' => $entryHead->findTitle(),
    //     'description' => $entryHead->findDescription(),
    //     'subjectCategory' => $entryHead->getSubjectCategory(),
    // ];
?>

<div class="row">
    <div class="col-xl-12 col-md-12">
<?php
    ViewTools::displayComponent('dashkit/card', [
        'title' => $entryHead->findTitle(),
        'titleLink' => '/asc/scaleBuilder/scale/'.$ascScale->getId(),
        'body' => $entryHead->findDescription(),
        'additionalCardHeaderClassString' => 'bg-primary',
        'additionalCardHeaderLinkClassString' => 'text-white',
        'editOnClick' => 'AscScaleLister.editScale(event, \''.$ascScale->getId().'\');'
    ]);
?>
    </div>
</div>
<?php endforeach; ?>