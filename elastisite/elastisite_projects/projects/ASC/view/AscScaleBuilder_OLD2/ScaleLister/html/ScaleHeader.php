<?php 
$data = $ascScaleHeader;
unset($data['subjectCategory']);
// $data['iconset'] = [
//     'Edit',
//     // 'CreateFrame',
//     // 'RemoveFrame',
//     'Delete'
// ];

$data['iconsetConfig'] = [
    'Edit' => [
        'onClickFunction' => 'AscScaleLister.edit('.$ascScaleHeader['id'].');'
    ],
    // 'CreateFrame' => [

    // ],
    // 'RemoveFrame' => [

    // ],
    'Delete' => $ascScaleRepository->isDeletable($ascScaleHeader['id']) ? [
        'onClickFunction' => 'AscScaleLister.initDeleteScale('.$ascScaleHeader['id'].', \''.$ascScaleHeader['title'].'\');'
    ] : null,
];
include('ScaleHeaderIconbar.php');
?>