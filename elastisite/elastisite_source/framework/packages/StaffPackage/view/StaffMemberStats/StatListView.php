
<style>
    .statCard {
        /* border: 1px solid #c0c0c0; */
        padding: 20px;
        margin-left: 0px;
        margin-right: 0px;
        margin-bottom: 20px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        /* max-width: 240px; */
    }

    .statCard-header {
        color: #0f5669;
        /* font-weight: bold; */
    }

    .statCard-error {
        background-color: #f49696; /* Vagy a kívánt piros szín */
    }

    .statCard-good {
        background-color: #e1e1e1; /* Vagy a kívánt zöld szín */
    }
</style>

<div class="row">
<?php 
// dump($staffMemberStats);
?>
    <?php foreach ($staffMemberStats['seriesData'] as $staffMemberStatsRow): ?>
        <?php
        // dump($staffMemberStatsRow);exit;
        $staffMemberStatId = $staffMemberStatsRow['staffMemberStatId'] ?? '';
        $points = $staffMemberStatsRow['points'];
        $isStaffMember = true; // Az isStaffMember feltételt itt állíthatod be

        // Kártya színének meghatározása
        $cardStatus = ($points === null) ? 'error' : 'good'; // Vagy más szín, ha nem piros-zöldet szeretnél

        // Kártya fejlécének elkészítése
        $year = $staffMemberStatsRow['year'];
        $weekId = $staffMemberStatsRow['weekId'];
        $weekNumber = $staffMemberStatsRow['weekNumber'];
        $cardHeader = '<b>' . $year . '</b> - <b>' . $weekNumber . '</b>. ' . trans('week');
        $points = $staffMemberStatsRow['points'] ? : '';

        // Kártya ID létrehozása
        $cardId = 'statCard_' . $weekId;

        // Input mező eseménykezelőjének elkészítése
        $onBlurHandler = "ManageStaffMemberStats.initSaveStat('$year', '$weekNumber')";
        ?>

        <div class="col-4" style="padding-top: 20px;">
            <div id="<?php echo $cardId; ?>" class="statCard <?php echo 'statCard-' . $cardStatus; ?>">
                <div class="statCard-header"><?php echo $cardHeader; ?></div>

                <?php if (empty($points)): ?>
                <div class="statCard-points-container"><b><?php echo trans('no.points.added.yet'); ?></b></div>
                <?php else: ?>
                <div class="statCard-points-container"><?php echo trans('points'); ?>: <b><?php echo $points; ?></b></div>
                <?php endif; ?>

                    <?php if ($isStaffMember): ?>
                    <input type="text" id="statCard_input_<?php echo $weekId; ?>" onblur="<?php echo $onBlurHandler; ?>">
                    <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>