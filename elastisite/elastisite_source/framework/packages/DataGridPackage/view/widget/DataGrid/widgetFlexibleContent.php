<?php

use framework\packages\UXPackage\service\ViewTools;
use projects\ASC\service\AscTechService;

App::getContainer()->wireService('UXPackage/service/ViewTools');
App::getContainer()->wireService('projects/ASC/service/AscTechService');

// dump($orderByField);
// dump($orderByDirection);
// dump($dataGrid);
// dump($posts);
// dump($pager);

    ViewTools::displayComponent('dashkit/tableCard', [
        'tableData' => $tableData
    ]);
?>