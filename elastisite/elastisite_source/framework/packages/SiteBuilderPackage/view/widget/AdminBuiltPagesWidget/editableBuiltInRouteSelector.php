<?php  
// dump($editableRoutes);
$pageToolView = $viewTools->create('pageTool');
?>
<div id="editBuiltPage-editableRoutes-showDedicatedRoutes"><a href="" onclick="EditBuiltPageModal.showDedicatedRoutes(event);"><?php echo trans('choose.dedicated.route'); ?></a></div>
<div id="editBuiltPage-editableRoutes-hideDedicatedRoutes" style="display: none;"><a href="" onclick="EditBuiltPageModal.hideDedicatedRoutes(event);"><?php echo trans('hide.dedicated.routes'); ?></a></div>
<div id="editBuiltPage-editableRoutes-container" style="display: none;">
<?php foreach ($editableRoutes as $editableRoute): ?>
    <div id="" data-routename="<?php echo $editableRoute['name']; ?>" class="tagFrame-col-manualWidth editBuiltPage-editableRoute" style="height: 100%; position: static; cursor: pointer;">
        <div class="tag-light" style="min-height: 160px;">
            <table style="width: 180px;">
                <tbody>
                    <tr>
                        <td id="">
                            <b><?php echo trans($editableRoute['title']); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td id="">
                            <?php echo $pageToolView->getParamChainString($editableRoute['name']); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
</div>
<div style="clear: both;"></div>