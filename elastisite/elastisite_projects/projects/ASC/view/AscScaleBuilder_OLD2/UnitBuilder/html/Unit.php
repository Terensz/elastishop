<?php 
// dump($unitData);
// $data = $unitData;
// $id = $unitData['ascUnitId'];

use projects\ASC\service\AscTechService;
use projects\ASC\service\AscSaveService;

App::getContainer()->wireService('projects/ASC/service/AscTechService');
App::getContainer()->wireService('projects/ASC/service/AscSaveService');

// dump($unitType);
$ascUnitId = $unitData['ascUnitId'];
$parentId = $unitData['parentId'];
$subject = $unitData['subject'];
$title = $unitData['mainEntryTitle'];
$description = $unitData['mainEntryDescription'];
$unitType = isset($unitType) ? $unitType : 'primary';
$data = [
    'ascUnitId' => $ascUnitId,
    'title' => $title,
    'unitType' => isset($unitType) ? $unitType : 'adminScale',
    'subject' => $subject
];
// $data = $ascScaleHeader;
// unset($data['subjectCategory']);
// // $data['iconset'] = [
// //     'Edit',
// //     // 'CreateFrame',
// //     // 'RemoveFrame',
// //     'Delete'
// // ];

// dump($subject);

$isDeletable = $unitData['isDeletable'];
$draggableClass = ' UnitBuilder-Unit-draggable';

// $draggableClassExpansion = '';
// if ($subject == AscTechService::SUBJECT_PLAN) {
//     if (isset($unitBuilderData['planningEntryData']['programs'][$ascUnitId])) {
//         $isDeletable = false;
//         // $draggableClassExpansion = '';
//     }
// } if ($subject == AscTechService::SUBJECT_PROGRAM) {
//     if (isset($unitBuilderData['planningEntryData']['projects'][$ascUnitId])) {
//         $isDeletable = false;
//         // $draggableClassExpansion = '';
//     }
//     if (isset($unitBuilderData['planningEntryData']['targetsOfPrograms'][$ascUnitId])) {
//         $isDeletable = false;
//         // $draggableClassExpansion = '';
//     }
// }
// if ($subject == AscTechService::SUBJECT_PROJECT) {
//     if (isset($unitBuilderData['planningEntryData']['targetsOfProjects'][$ascUnitId])) {
//         $isDeletable = false;
//         // $draggableClassExpansion = '';
//     }
// }

$draggableClassExpansion = $isDeletable ? '' : ' UnitBuilder-Unit-draggable-sortOnly';

$draggableClass .= $draggableClassExpansion;

$data['iconsetConfig'] = [
    'Edit' => [
        'onClickFunction' => 'AscScaleBuilder.editUnit('.$ascUnitId.');'
    ],
    // 'CreateFrame' => [

    // ],
    // 'RemoveFrame' => [

    // ],
];

if ($isDeletable) {
    $deletedItemReferenceTitle = !empty($title) ? $title : trans('untitled.unit');
    $data['iconsetConfig']['Delete'] = [
        'onClickFunction' => 'AscScaleBuilder.initDeleteUnit('.$ascUnitId.', \''.$deletedItemReferenceTitle.'\');'
    ];
}

// dump($isDeletable);
$dragDebug = '';
$dragDebug = '('.AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_UNIT.'-'.$parentId.'-'.$subject.') ';
?>

<div class="UnitBuilder-Unit<?php echo $draggableClass; ?>" id="UnitBuilder-Unit-<?php echo $ascUnitId; ?>" 
    data-unitid="<?php echo $ascUnitId; ?>"
    data-subject="<?php echo $subject; ?>"
    >
<?php 
include('UnitIconbar.php');
include('UnitBody.php');
// if (count($unitData['files']) > 0) {
//     include('UnitFiles.php');
// }
// if ($unitData['dueDate']) {
//     include('UnitDueDate.php');
// }
?>
</div>
