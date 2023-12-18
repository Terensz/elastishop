<?php 
// dump($routeId);
// dump($routePath);
?>

<div data-routeid="<?php echo $routeId; ?>" data-routename="<?php echo $routeName; ?>" data-route="<?php echo $routePath; ?>" class="info-card<?php echo $sortingClasses; ?>" style="width: 100%;">
    <div class="<?php echo $tagClass; ?>">
        <div style="cursor: pointer; float: left;">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td class="tag-title" id="">
                            <div id="UserAreaMenuEditor_titleContainer_<?php echo $routeId; ?>" class="UserAreaMenuEditor_titleContainer">
                                <?php echo $title; ?>
                            </div>
                            <div id="UserAreaMenuEditor_titleInputContainer_<?php echo $routeId; ?>" class="UserAreaMenuEditor_titleInputContainer" style="display: none;">
                                <div style="float: left !important;">
                                    <input id="UserAreaMenuEditor_titleInput_<?php echo $routeId; ?>" class="UserAreaMenuEditor_titleInput form-control form-control-lg" type="text" placeholder="<?php echo $title; ?>">
                                </div>
                                <button type="button" onclick="UserAreaMenuEditor.saveTitle('<?php echo $routeId; ?>');" class="btn btn-success"><?php echo trans('save'); ?></button>
                                <button type="button" onclick="UserAreaMenuEditor.cancelEditTitle();" class="btn btn-danger"><?php echo trans('cancel'); ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tag-content" id="">
                            <?php echo $routeName; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style=" float: right;">
        <?php if ($editButtonOnClick): ?>
            <button type="button" onclick="<?php echo $editButtonOnClick; ?>" class="btn btn-info"><?php echo trans('edit.title'); ?></button>
        <?php endif; ?>
            <button type="button" onclick="<?php echo $addOrRemoveButtonOnClick; ?>" class="btn btn-<?php echo $addOrRemoveButtonType; ?>"><?php echo $addOrRemoveButtonText; ?></button>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>