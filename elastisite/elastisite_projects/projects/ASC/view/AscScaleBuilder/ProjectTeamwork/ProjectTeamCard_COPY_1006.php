<?php 

include('definitions/pathToCommonComponents.php');

?>
<div class="card">
    <div class="bg-primary text-white card-header d-flex justify-content-between align-items-center">
        <div class="card-header-textContainer">
            <div class="card-header-iconContainer-left" style="line-height: 1;">
                <a class="" href="" onclick="ProjectTeamwork.editProjectTeam(event, '<?php echo $projectTeamDataRow['projectTeamId']; ?>');">
                    <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
                </a>
            </div>
            <h6 class="mb-0 text-white"><?php echo $projectTeamDataRow['projectTeamName']; ?></h6>
            <?php 
            // echo "&nbsp; (".App::getElapsedLoadingTime().")";
            ?>
        </div>
    </div>
    <div class="card-body table-border-style">

    <!-- <div class="row">
        <div class="col-md-12 card-pack-header">
            <h4>Főcélok</h4>
        </div>
    </div> -->

    <div class="d-flex align-items-center" style="line-height: 0;">
        <div class="card-header-iconContainer-left">
            <a class="" href="" onclick="ProjectTeamwork.editProjectTeam(event, '<?php echo $projectTeamDataRow['projectTeamId']; ?>');">
                <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
            </a>
        </div>
        <div class="flex-grow-1" style="height: 100%;">
            <!-- <div style="display: flex; align-items: center; height: 40px;">
                <h5 class="m-0"><?php echo $projectTeamDataRow['projectTeamName']; ?></h5>
            </div> -->
        </div>
    </div>

    <?php 
    // if ($scalePermissions['invite']): 
    ?>
    <!-- <div class="mb-4">
        <button type="button" onclick="AscScaleBuilder.inviteUserModal();" class="btn btn-success">Új tag meghívása a csapatba</button>
    </div> -->
    <?php 
    // endif; 
    ?>

    <!-- <div class="d-flex align-items-center" style="line-height: 0;">
        <div class="card-header-iconContainer-left">
            <a class="" href="" onclick="AscScaleBuilder.editUnit(event, '213000', '215075');">
                <img src="/public_folder/plugin/Bootstrap-icons/edit.svg">
            </a>
        </div>
        <div class="card-pack-header text-center" style="display: inline-block; vertical-align: middle;">
            <h4>Főcélok</h4>
        </div>
    </div> -->

    <?php 
    // include($pathToCommonComponents.'ScaleTeamTable.php');
    ?>
    </div>
</div>
<script>

</script>