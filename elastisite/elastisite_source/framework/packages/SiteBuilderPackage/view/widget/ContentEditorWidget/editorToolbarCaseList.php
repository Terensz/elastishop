<?php

use framework\component\helper\StringHelper;

?>
    <?php foreach ($contentEditor->getSortedContentEditorUnitCases() as $unitCase): ?>
    <div class="row contentEditorToolbar-unitCase-container unitCase-sorting-item-<?php echo $contentEditorId; ?>" data-id="<?php echo $unitCase->getId(); ?>" id="ContentEditorToolbar_unitCase_<?php echo $unitCase->getId(); ?>">
        <div class="col-xl-9 col-md-8 contentEditorToolbar-unit-rail" id="contentEditorUnit_units_sortableContainer_<?php echo $unitCase->getId(); ?>" style="float: left; padding: 0px; padding-top: 10px; margin: 0px;">
        <?php
        // dump($unitCase);
        ?>

        <?php foreach ($unitCase->getSortedContentEditorUnits() as $unit): ?>
            <?php
            //  dump($unit->getId());
            ?>
            <!-- <div style="clear: both;"></div> -->
            <div class="ui-state-default">
                <div class="row contentEditorToolbar-unit-container unit-sorting-item-<?php echo $unitCase->getId(); ?>" data-id="<?php echo $unit->getId(); ?>">
                    <div class="col-xl-9 col-md-8 contentEditorToolbar-unit-description">
                <?php if ($unit->getDescription()): ?>
                        <?php echo StringHelper::cutLongString(strip_tags(html_entity_decode($unit->getDescription())), 27); ?>
                <?php else: ?>
                        <i><?php echo trans('no.description.yet'); ?></i>
                <?php endif; ?>
                    </div>
                    <div class="col-xl-3 col-md-4 contentEditorToolbar-unit-operations" style="padding: 8px; text-align: center;">
                        <a href="" onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.editContentEditorUnit(event, '<?php echo $unit->getId(); ?>');"><?php echo trans('modify'); ?></a> | 
                        <a href="" onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.removeContentEditorUnit(event, '<?php echo $unit->getId(); ?>');"><?php echo trans('delete'); ?></a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        </div>
        <div class="col-xl-3 col-md-4" style="text-align: center;">
            <a href="" onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.addContentEditorUnit(event, '<?php echo $unitCase->getId(); ?>');"><?php echo trans('add.unit'); ?></a> | 
            <a href="" onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.editContentEditorUnitCase(event, '<?php echo $unitCase->getId(); ?>');"><?php echo trans('modify'); ?></a> | 
            <a href="" onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.removeContentEditorUnitCase(event, '<?php echo $unitCase->getId(); ?>');"><?php echo trans('delete'); ?></a>
            <!-- <button type="button" class="btn btn-primary btn-xs" style="height: 36px; padding-bottom: 30px !important;">Add text</button> -->
        </div>
    </div>

<script>
$( "#contentEditorUnit_units_sortableContainer_<?php echo $unitCase->getId(); ?>" ).sortable({
    create: function(event, ui) {
        // console.log(event.type);
        if (event.type != 'sortcreate') {
            ContentEditorSorter_<?php echo $contentEditorId; ?>.sortContentEditorUnits('<?php echo $unitCase->getId(); ?>');
        }
    },
    stop: function(event, ui) {
        ContentEditorSorter_<?php echo $contentEditorId; ?>.sortContentEditorUnits('<?php echo $unitCase->getId(); ?>');
    }
});
</script>

    <?php endforeach; ?>