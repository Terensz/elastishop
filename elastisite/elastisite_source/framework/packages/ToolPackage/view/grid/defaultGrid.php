<div class="grid-container">
    <div class="row grid-title-row">
<?php
$colSumAdder = 0;
$colCounter = 0;
// dump($repository);exit;
$originalOrdererStr = '<a class="pseudoLink" href="" style="display: inline;" onclick="'.ucfirst($grid->getGridName()).'Grid.orderBy({{ orderByProp }}, {{ orderByDirection }})">
<img class="window-button grid-title-cell-background" style="width: 12px; color: #ffffff; border: 0px;" src="'.$container->getUrl()->getHttpDomain().'/public_folder/plugin/Bootstrap-icons/chevron{{ orderByChevron }}.svg">
</a>';
foreach ($grid->getProperties() as $property) {
    //dump($property);
    if (($property['name'] != 'id' || ($property['name'] == 'id' && $grid->getShowId())) && $property['colWidth']) {
?>
        <div class="col<?php echo $property['colWidth']; ?> grid-title-cell grid-title-cell-background"><?php echo $property['title']; ?></div>
<?php
        $colSumAdder += abs($property['colWidth']);
        $colCounter++;
    }
}
if ($grid->getAddDeleteLink()) {
    $deleteColWidth = (12 - $colSumAdder);
?>
    <div class="col-<?php echo $deleteColWidth; ?> grid-title-cell grid-title-cell-background"><?php echo trans('delete'); ?></div>
<?php
    $colCounter++;
}
?>
    </div>
<?php
if (!is_array($grid->getData())) {
    $grid->setData(array());
}

if (count($grid->getData()) == 0) {
?>
<div class="row grid-body-row breakLongText" style="padding: 10px;">
    <?php echo trans('no.result'); ?>
</div>
<?php 
}

foreach ($grid->getData() as $row) {
    // $id = isset($row['id']) ? $row['id'] : null;
    $additionalClass = '';
    $addClassBy = $grid->getAddClassBy();
    if (is_array($addClassBy) && count($addClassBy) > 0) {
        // dump($addClassBy);
        foreach ($addClassBy as $addClassByLoop) {
            if ($row[$addClassByLoop['column']] == $addClassByLoop['value']) {
                $additionalClass = ' '.$addClassByLoop['class'];
            }
        }
    }
?>
    <div class="row grid-body-row breakLongText<?php echo $additionalClass; ?>">
<?php
    foreach ($grid->getProperties() as $property) {
        $backgroundColorStr = '';
        if (isset($property['backgroundColor'])) {
            $backgroundColor = isset($row[$property['backgroundColor']]) ? $row[$property['backgroundColor']] : '#ffffff';
            $backgroundColorStr = ' style="background-color: '.$backgroundColor.';"';
        }
        if (($property['name'] != 'id' || ($property['name'] == 'id' && $grid->getShowId()))
            && $property['colWidth']) {
            $onClickStr = "";
            if (isset($row['id'])) {
                $onClickStr = " onclick=\"".ucfirst($grid->getGridName())."Grid.edit('".$row['id']."');\"";
            }
            // dump($property['name']);
?>
        <div<?php echo $backgroundColorStr.$onClickStr; ?>
            class="triggerModal col<?php echo $property['colWidth']; ?> grid-body-cell">
<?php
        if (!isset($property['backgroundColor'])) {
            echo trans(\framework\kernel\utility\BasicUtils::arrayToString($row[$property['name']], ', '));
        }
?>
        </div>
<?php
        }
    }




    if ($grid->getAddDeleteLink()) {
        $deleteColWidth = (12 - $colSumAdder);
    ?>
        <div class="col-<?php echo $deleteColWidth; ?> grid-body-cell">
    <?php 
        if (!isset($repository) || (isset($repository) && $repository->isDeletable($row['id']))) {
    ?>
            <a href="" class="triggerModal grid-deleteElement" onclick="<?php echo ucfirst($grid->getGridName()); ?>Grid.delete('<?php echo $row['id']; ?>');"><?php echo trans('delete'); ?></a>
    <?php 
        } else {
    ?>
        <!-- <div<?php echo $onClickStr; ?> -->
    <?php
            echo trans('undeletable');
    ?>
        <!-- </div> -->
    <?php
        }
    ?>
        </div>
    <?php
    }
    ?>
    </div>
<?php
}

// dump($grid->getGridName());exit;
?>
</div>

<script>

if (typeof(<?php echo ucfirst($grid->getGridName()); ?>Grid) == 'undefined') {
    console.log('new <?php echo ucfirst($grid->getGridName()); ?>Grid');
    var <?php echo ucfirst($grid->getGridName()); ?>Grid = {};
}


<?php echo ucfirst($grid->getGridName()); ?>Grid.new = function() {
    $('#editorModalLabel').html('');
    $('#editorModalBody').html('');
    <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(null);
    $('#editorModal').modal('show');
};
<?php echo ucfirst($grid->getGridName()); ?>Grid.edit = function(id) {
    $('#editorModalLabel').html('');
    $('#editorModalBody').html('');
    <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(id);
    $('#editorModal').modal('show');
};
<?php echo ucfirst($grid->getGridName()); ?>Grid.delete = function(id) {
    if (id == undefined || id === null || id === false) {
        return false;
    }
    $('#confirmModalConfirm').attr('onClick', "<?php echo ucfirst($grid->getGridName()); ?>Grid.deleteConfirmed(" + id + ");");
    $('#confirmModalBody').html('<?php echo trans('are.you.sure'); ?>');
    $('#confirmModal').modal('show');
};
<?php echo ucfirst($grid->getGridName()); ?>Grid.orderBy = function(prop, direction) {
    console.log('grid: orderBy');
    <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.search(null, {'prop': prop, 'direction': direction});
};
<?php echo ucfirst($grid->getGridName()); ?>Grid.deleteConfirmed = function(id) {
    <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.delete(id);
    $('#confirmModal').modal('hide');
};




$('body').on('click', '.triggerModal', function (e) {
    e.preventDefault();
});
</script>

<?php 
/*
var <?php echo ucfirst($grid->getGridName()); ?>Grid = {
    new: function() {
        $('#editorModalLabel').html('');
        $('#editorModalBody').html('');
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(null, null);
        $('#editorModal').modal('show');
    },
    edit: function(id) {
        $('#editorModalLabel').html('');
        $('#editorModalBody').html('');
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(id, null);
        $('#editorModal').modal('show');
    },
    delete: function(id) {
        if (id == undefined || id === null || id === false) {
            return false;
        }
        $('#confirmModalConfirm').attr('onClick', "<?php echo ucfirst($grid->getGridName()); ?>Grid.deleteConfirmed(" + id + ");");
        $('#confirmModal').modal('show');
    },
    orderBy: function(prop, direction) {
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.call(null, {'prop': prop, 'direction': direction});
    },
    deleteConfirmed: function(id) {
        <?php echo ucfirst($grid->getGridName()); ?>GridAjaxInterface.delete(id);
        $('#confirmModal').modal('hide');
    }
};
*/
?>