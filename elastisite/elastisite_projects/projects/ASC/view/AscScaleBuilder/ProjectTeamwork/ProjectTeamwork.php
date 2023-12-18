<?php  

$entryHead = $ascScale->getAscEntryHead();

// include('definitions/pathToCommonComponents.php');
// include('definitions/pathToTeamwork.php');
?>

<div class="pc-container">
    <div class="pcoded-content card-container">

        <div class="mb-4">
            <button type="button" onclick="ProjectTeamwork.newProjectTeam(event, false);" class="btn btn-success">Új munkacsoport hozzáadása</button>
        </div>

        <!-- <div class="row">
            <div class="col-xl-12 col-md-12">
                <button type="button" onclick="ProjectTeamwork.newProjectTeam(event, false);" class="btn btn-success">Új csapat hozzáadása</button>
            </div>
        </div> -->

        <?php foreach ($projectTeamworkData['projectTeamData'] as $projectTeamDataRow): ?>
        <?php 
            $projectTeamUserData = $projectTeamworkData['projectTeamUserData'];
            $projectTeamUserDataBlock = isset($projectTeamUserData[$projectTeamDataRow['projectTeamId']]) ? $projectTeamUserData[$projectTeamDataRow['projectTeamId']] : [];
        ?>
        <div class="row">
            <div class="col-xl-12 col-md-12">
            <?php 
            include('ProjectTeamCard.php'); 
            ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<?php 
// dump($projectTeamUserData);
// dump($projectTeamData);
?>