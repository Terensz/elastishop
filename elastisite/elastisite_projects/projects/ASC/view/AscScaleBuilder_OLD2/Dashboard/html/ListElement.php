<?php

use projects\ASC\service\AscUnitBuilderService;

App::getContainer()->wireService('projects/ASC/service/AscUnitBuilderService');

    $currentPath = __DIR__;
    $parentPath = dirname($currentPath);
    $pathToBuilder = $parentPath . '/../UnitBuilder/';

    $unitData = AscUnitBuilderService::getUnitData($ascUnit)['data'];
    $unitType = 'primary';
    // dump($unitData);
?>
<!-- <div class="list-element">
    
    <?php 
    //include($pathToBuilder.'html/Unit.php');
    // echo $todaysDueUnit; 
    ?>
</div> -->