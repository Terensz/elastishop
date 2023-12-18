<?php 

use projects\ASC\service\AscSaveService;
// use projects\ASC\service\AscTechService;

App::getContainer()->wireService('projects/ASC/service/AscSaveService');
// App::getContainer()->wireService('projects/ASC/service/AscTechService');

?>


<div class="UnitBuilder-UnitPanel UnitBuilder-UnitWrapper UnitBuilder-UnitWrapper-<?php echo $subject; ?>"<?php echo $idStr; ?> 
    data-parenttype="<?php echo AscSaveService::PLACEHOLDER_TARGET_PARENT_TYPE_UNIT; ?>"
    data-parentid="<?php echo $parentId; ?>"
    data-subject="<?php echo $subject; ?>"
    data-unitid="<?php echo $ascUnitId; ?>"
    >
<?php 
include('Unit.php');
?>
</div>