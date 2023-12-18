<?php  

$entryHead = $ascScale->getAscEntryHead();

// include('definitions/pathToCommonComponents.php');
// include('definitions/pathToTeamwork.php');
?>

<div class="pc-container">
    <div class="pcoded-content card-container">

        <!-- <div class="row">
            <div class="col-xl-12 col-md-12">
            <?php 
            // include($pathToTeamwork.'Teamwork.php'); 
            ?>
            </div>
        </div> -->

        <?php 
        if ($columnView) {
            include('ColumnViewAll.php');
        } else {
            include('ListViewAll.php');
        }
        ?>

    </div>
</div>