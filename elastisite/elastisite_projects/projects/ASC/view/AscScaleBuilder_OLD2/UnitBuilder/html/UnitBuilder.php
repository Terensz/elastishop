<?php

// use projects\ASC\service\AscTechService;

// App::getContainer()->wireService('projects/ASC/service/AscTechService');

?>

<div class="row" style="margin-right: 7px; margin-top: 0px;">
<?php
// $totalNumberOfUnitPanels = $unitPanelData['totalNumberOfUnitPanels'];

if ($unitBuilderData) {

    // $ascScaleHeader = [
    //     'id' => $ascScale->getId(),
    //     'title' => $entryHead->findTitle(),
    //     'description' => $entryHead->findDescription(),
    //     'subjectCategory' => $entryHead->getSubjectCategory(),
    // ];

    // dump($unitBuilderData['totalNumberOfUnitPanels']);

    $numberOfSubjectPanels = $unitBuilderData['totalNumberOfSubjectPanels'];
    $boostrapWidthUnitMultiplier = 12 / $unitBuilderData['totalNumberOfUnitPanels'];
    $counter = 0;
    $subjectSpots = array_keys($unitBuilderData['subjectPanels']);
    $placeholdersAlreadyApplied = [];
    // dump($subjectSpots);

    foreach ($unitBuilderData['subjectPanels'] as $subjectSpot => $subjectPanelData) {

        // dump($subjectPanelData);

        // $counter = 0;
        // $primarySubject = null;
        // foreach ($subjectPanelData['mainData'] as $loopPrimarySubject => $mainDataRow) {
        //     if ($counter == 0) {
        //         $primarySubject = $loopPrimarySubject;
        //     }
        //     $counter++;
        // }

        // dump($subjectPanelData['mainData']);

        // echo '<pre>';
        // var_dump($subjectPanelData);
        // echo '</pre>';

        $numberOfUnitPanels = count($subjectPanelData['mainProperties']);
        $subjectPanelBootstrapWidthUnits = $numberOfUnitPanels * $boostrapWidthUnitMultiplier;

        include('PrimarySubjectPanel.php');

        // if ($primarySubject == AscTechService::SUBJECT_STAT) {
        //     include('StatisticsPanel.php');
        // } else {
        //     include('SubjectPanel.php');
        // }
        // $counter++;
    }

    // echo '<pre>';
    // dump($unitBuilderData);
}

// if ($unitPanelData['totalNumberOfSubjectPanels'] > 0) {
//     for ($i = 0; $i < $unitPanelData['totalNumberOfSubjectPanels']; $i++) {
//         $subjectPanelId = $i + 1;

//         include('SubjectPanel.php');
//     }
// }

?>
</div>
