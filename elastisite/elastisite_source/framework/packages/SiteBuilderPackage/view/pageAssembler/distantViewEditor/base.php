<style>
.distantViewEditor-sheetWidth {
    width: 90%;
    /* border: 1px solid #c0c0c0; */
    margin: 0 auto;
}
.distantViewEditor-sheet {
    /* height: 400px; */
    /* margin: 0 auto; */
    /* border: 1px solid #c0c0c0; */
}
.distantViewEditor-widget-bannerWidget {
    height: 60px;
    /* margin: 0 auto; */
    background-color: #3f3f3f;
    border: 1px solid #000;
    color: #fff;
    text-align: center;
}
.distantViewEditor-widget-footerWidget {
    height: 40px;
    /* margin: 0 auto; */
    background-color: #3f3f3f;
    border: 1px solid #000;
    color: #fff;
    text-align: center;
}
.distantViewEditor-widget-menuWidget {
    height: 30px;
    /* margin: 0 auto; */
    background-color: #737373;
    border: 1px solid #000;
    color: #fff;
    text-align: center;
}
.distantViewEditor-col {
    border: 1px solid #c0c0c0;
}
.distantViewEditor-publicWidget {
    margin: 6px;
    padding: 6px;
    border: 1px solid #c0c0c0;
    word-break: break-all;
}
.distantViewEditor-publicWidget-title {
    font: 15px DefaultFontBold;
}
/*
sortable
*/
.sortable { 
    list-style-type: none; 
}
.sortable li span { 
    position: absolute; 
}
.ui-sortable { 
}
</style>
<?php 
// dump($builtPageData['widgetDetails']);
// dump($builtPageId);
// dump($builtPageData);
?>
<div class="distantViewEditor-sheet">
    <div class="distantViewEditor-widget-bannerWidget distantViewEditor-sheetWidth">
        <!-- Banner -->
        <?php echo trans('banner'); ?>
    </div>

    <div class="distantViewEditor-widget-menuWidget distantViewEditor-sheetWidth">
        <?php echo trans('menu'); ?>
    </div>

    <div class="distantViewEditor-sheetWidth">
        <div class="row">
            <div class="col-5">
            <?php if ($builtPageData['builtPageObject']->getNumberOfPanels() == 1): ?>
                <a href="" onclick="DistantViewEditor.addLeftPanel(event);" onclick=""><?php echo trans('add.left.panel'); ?></a>
            <?php endif; ?>
            <?php if ($builtPageData['builtPageObject']->getNumberOfPanels() == 2 && (isset($builtPageData['widgetsAlreadyUsed']['left']) && empty($builtPageData['widgetsAlreadyUsed']['left']))): ?>
                <a href="" onclick="DistantViewEditor.removeLeftPanel(event);" onclick=""><?php echo trans('remove.left.panel'); ?></a>
            <?php endif; ?>
            </div>
            <div class="col-7">
                &nbsp;
            </div>
        </div>
    </div>

    <div class="distantViewEditor-sheetWidth">
        <div class="row">
            <?php if ($builtPageData['builtPageObject']->getNumberOfPanels() == 2): ?>
            <div id="publicWidget-left-sortable" class="col-5">
                <?php foreach ($builtPageData['widgetsAlreadyUsed']['left'] as $usedLeftWidget): ?>
                    <div data-widget="<?php echo $usedLeftWidget; ?>" class="distantViewEditor-publicWidget ui-sortable sortable sorting-item-left">
                        <div class="distantViewEditor-publicWidget-title">
<?php 
$loopWidgetData = $builtPageData['publicWidgets'][$usedLeftWidget];
?>
                            <?php echo $usedLeftWidget; ?>
                        </div>
                        <div>
                            <a href="" onclick="DistantViewEditor.removeWidget(event, 'left', '<?php echo $usedLeftWidget; ?>');" onclick=""><?php echo trans('remove.widget'); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div id="publicWidget-main-sortable" class="<?php if ($builtPageData['builtPageObject']->getNumberOfPanels() == 1) { echo 'col-12'; } elseif ($builtPageData['builtPageObject']->getNumberOfPanels() == 2) {echo 'col-7';} ?>">
                <?php foreach ($builtPageData['widgetsAlreadyUsed']['main'] as $usedMainWidget): ?>
                    <div data-widget="<?php echo $usedMainWidget; ?>" class="distantViewEditor-publicWidget ui-sortable sortable sorting-item-main">
                        <div class="distantViewEditor-publicWidget-title">
<?php 
$loopWidgetData = $builtPageData['publicWidgets'][$usedMainWidget];
?>
                            <?php echo $usedMainWidget; ?>
                        </div>
                        <div>
                            <a href="" onclick="DistantViewEditor.removeWidget(event, 'main', '<?php echo $usedMainWidget; ?>');" onclick=""><?php echo trans('remove.widget'); ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="distantViewEditor-widget-footerWidget distantViewEditor-sheetWidth">
        <!-- Footer -->
        <?php echo trans('footer'); ?>
    </div>

    <div class="distantViewEditor-sheetWidth">
    <i><?php echo trans('add.widgets.to.panels'); ?></i>
    </div>

    <div class="distantViewEditor-sheetWidth">
        <div class="row">
            <?php if ($builtPageData['builtPageObject']->getNumberOfPanels() == 2): ?>
            <div class="col-5">
                <?php if (!$builtPageData['positionFull']['left']): ?>
                    <?php foreach (isset($builtPageData['availableWidgets']['left']) ? $builtPageData['availableWidgets']['left'] : [] as $availableLeftWidget): ?>
                    <div class="distantViewEditor-publicWidget">
                        <div class="distantViewEditor-publicWidget-title">
<?php 
$loopWidgetData = $builtPageData['publicWidgets'][$availableLeftWidget];
?>
                            <?php echo $availableLeftWidget; ?>
                        </div>
                        <div>
                            <a href="" onclick="DistantViewEditor.addWidget(event, 'left', '<?php echo $availableLeftWidget; ?>');" onclick=""><?php echo trans('add.widget'); ?></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php echo trans('no.more.room.on.this.position'); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="<?php if ($builtPageData['builtPageObject']->getNumberOfPanels() == 1) { echo 'col-12'; } elseif ($builtPageData['builtPageObject']->getNumberOfPanels() == 2) {echo 'col-7';} ?>">
                <?php if (!$builtPageData['positionFull']['main']): ?>
                    <?php foreach (isset($builtPageData['availableWidgets']['main']) ? $builtPageData['availableWidgets']['main'] : [] as $availableMainWidget): ?>
                    <div class="distantViewEditor-publicWidget">
                        <div class="distantViewEditor-publicWidget-title">
<?php 
$loopWidgetData = $builtPageData['publicWidgets'][$availableMainWidget];
?>
                            <?php echo $availableMainWidget; ?>
                        </div>
                        <div>
                            <a href="" onclick="DistantViewEditor.addWidget(event, 'main', '<?php echo $availableMainWidget; ?>');" onclick=""><?php echo trans('add.widget'); ?></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php echo trans('no.more.room.on.this.position'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
<script>
$( "#publicWidget-left-sortable").sortable({
    create: function(event, ui) {
        if (event.type != 'sortcreate') {
            DistantViewEditor.sortLeftWidgets(ui);
        }
    },
    stop: function(event, ui) {
        DistantViewEditor.sortLeftWidgets(ui);
    }
});
$( "#publicWidget-main-sortable").sortable({
    create: function(event, ui) {
        if (event.type != 'sortcreate') {
            DistantViewEditor.sortMainWidgets(ui);
        }
    },
    stop: function(event, ui) {
        DistantViewEditor.sortMainWidgets(ui);
    }
});
</script>