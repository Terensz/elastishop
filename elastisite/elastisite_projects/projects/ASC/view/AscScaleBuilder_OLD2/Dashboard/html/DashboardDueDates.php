<div class="UnitBuilder-SubjectPanel-container">

    <div class="entry-container">
        <div class="entry-title-container entry-title-background-blue"><b>Mai teendők</b></div>
        <div class="entry-body-container">
        <?php 
        // dump($todaysOneTimeDueUnits);
        ?>
    <?php if ((count($todaysOneTimeDueUnits) + count($todaysWeeklyRecurrenceDueUnits)) > 0): ?>
        <?php foreach ($todaysOneTimeDueUnits as $ascUnit): ?>
            <?php include('ListElement.php'); ?>
        <?php endforeach; ?>

        <?php foreach ($todaysWeeklyRecurrenceDueUnits as $ascUnit): ?>
            <?php include('ListElement.php'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo trans('the.list.is.empty'); ?>
    <?php endif; ?>
        </div>
    </div>

    <div class="entry-container">
        <div class="entry-title-container entry-title-background-red"><b>Lejárt teendők</b></div>
        <div class="entry-body-container">
    <?php if (count($expiredOneTimeDueUnits) > 0): ?>
        <?php foreach ($expiredOneTimeDueUnits as $ascUnit): ?>
            <?php include('ListElement.php'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo trans('the.list.is.empty'); ?>
    <?php endif; ?>
        </div>
    </div>

    <div class="entry-container">
        <div class="entry-title-container entry-title-background-green"><b>Lezárt teendők</b></div>
        <div class="entry-body-container">
    <?php if ((count($closedOneTimeDueUnits) + count($closedWeeklyRecurrenceDueUnits)) > 0): ?>
        <?php foreach ($closedOneTimeDueUnits as $ascUnit): ?>
            <?php include('ListElement.php'); ?>
        <?php endforeach; ?>

        <?php foreach ($closedWeeklyRecurrenceDueUnits as $ascUnit): ?>
            <?php include('ListElement.php'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo trans('the.list.is.empty'); ?>
    <?php endif; ?>
        </div>
    </div>

    <!-- <div class="entry-container">
        <div class="entry-title-container entry-title-background-grey"><b>Elnapolt teendők</b></div>
        <div class="entry-body-container">
    <?php if ((count($postponedOneTimeDueUnits) + count($postponedWeeklyRecurrenceDueUnits)) > 0): ?>
        <?php foreach ($postponedOneTimeDueUnits as $ascUnit): ?>
            <?php // include('ListElement.php'); ?>
        <?php endforeach; ?>

        <?php foreach ($postponedWeeklyRecurrenceDueUnits as $ascUnit): ?>
            <?php // include('ListElement.php'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php echo trans('the.list.is.empty'); ?>
    <?php endif; ?>
        </div>
    </div> -->

</div>

<style>
.entry-container {
    padding: 0px;
    margin: 10px;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
}
.entry-title-container {
    font-size: 18px;
    padding: 10px;
    margin: 0px;
    box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
}
.entry-body-container {
    /* font-size: 18px; */
    padding: 10px;
    margin: 0px;
}
.entry-title-background-blue {
    background-color: #1d8ab5;
    color: #fff;
}
.entry-title-background-red {
    background-color: #c6253b;
    color: #fff;
}
.entry-title-background-green {
    background-color: #377f33;
    color: #fff;
}
.entry-title-background-grey {
    background-color: #464646;
    color: #fff;
}
</style>

