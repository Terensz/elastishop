<?php

use projects\ASC\service\AscTechService;

App::getContainer()->wireService('projects/ASC/service/AscTechService');

?>

<div class="col-md-12 card-pack-header">
    <h4>[<?php echo trans(AscTechService::findSubjectConfigValue($packHeaderUnitData['data']['subject'], 'translationReferenceSingular')); ?>] <?php echo $packHeaderUnitData['data']['mainEntryTitle']; ?></h4>
</div>