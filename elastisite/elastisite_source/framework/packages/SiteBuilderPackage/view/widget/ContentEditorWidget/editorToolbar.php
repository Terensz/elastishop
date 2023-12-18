<?php 
// use framework\kernel\utility\BasicUtils;
?>
<form id="contentEditorBoard_toolbar_form" name="contentEditorBoard_toolbar_form" action="" method="POST" enctype="multipart/form-data">
    <div class="row" style="width: 100%;">

        <!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 toolbar-inputContainer">

            <div class="contentEditorToolbar-input-xsTitle"><b><?php

 echo trans('shadow.color'); ?></b></div>
            <div class="input-group toolbar-input">
                <input name="contentEditorBoard_toolbar_shadowColor" id="contentEditorBoard_toolbar_shadowColor" type="color" class="inputField toolbar-inputField contentEditorToolbar-inputField-<?php echo $contentEditorId; ?> form-control" value="#000000" aria-describedby="" placeholder="">
            </div>
        </div> -->
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 toolbar-inputContainer">
            <div class="contentEditorToolbar-input-xsTitle">&nbsp;</div>
            <div class="input-group toolbar-input">
                <button onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.addContentEditorUnitCase(event);" id="contentEditorBoard_toolbar_addContentEditorUnit" type="button" class="btn btn-success" style="width: 100%; padding-top: 8px;">
                    <?php echo trans('add.content.assembler.unit.container'); ?>
                </button>
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 toolbar-inputContainer">
            <div class="contentEditorToolbar-input-xsTitle"><b><?php echo trans('height'); ?></b></div>
            <div class="input-group toolbar-input">
<?php
$height = $contentEditor->getHeight();
?>
                <select name="contentEditorBoard_toolbar_height" id="contentEditorBoard_toolbar_height" class="inputField toolbar-inputField contentEditorToolbar-inputField-<?php echo $contentEditorId; ?> form-control">
                    <option value="0"<?php echo ((int)$height == 0 ? ' selected' : ''); ?>>0</option>
                    <option value="200"<?php echo ((int)$height == 200 ? ' selected' : ''); ?>>200</option>
                    <option value="400"<?php echo ((int)$height == 400 ? ' selected' : ''); ?>>400</option>
                    <option value="600"<?php echo ((int)$height == 600 ? ' selected' : ''); ?>>600</option>
                    <option value="800"<?php echo ((int)$height == 800 ? ' selected' : ''); ?>>800</option>
                    <option value="1000"<?php echo ((int)$height == 1000 ? ' selected' : ''); ?>>1000</option>
                    <option value="1200"<?php echo ((int)$height == 1200 ? ' selected' : ''); ?>>1200</option>
                </select>
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 toolbar-inputContainer">
            <div class="contentEditorToolbar-input-xsTitle"><b><?php echo trans('shadow'); ?></b></div>
            <div class="input-group toolbar-input">
<?php
$boxShadowStyle = $contentEditor->getBoxShadowStyle();
?>
                <select name="contentEditorBoard_toolbar_shadow" id="contentEditorBoard_toolbar_shadow" class="inputField toolbar-inputField contentEditorToolbar-inputField-<?php echo $contentEditorId; ?> form-control">
                    <option value="none"<?php echo ($boxShadowStyle == 'none' ? ' selected' : ''); ?>>Nincs</option>
<?php foreach ($contentEditor::BOX_SHADOW_STYLES as $styleKey => $style): ?>
                    <option value="<?php echo $styleKey; ?>"<?php echo ($boxShadowStyle == $styleKey ? ' selected' : ''); ?>><?php echo $styleKey; ?></option>
<?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 toolbar-inputContainer">
<?php if ($contentEditor->getContentEditorBackgroundImage()): ?>
            <div class="contentEditorToolbar-input-xsTitle"><b><?php echo trans('change.image'); ?></b></div>
            <div class="custom-file mt-3 mb-12 toolbar-input" style="padding-top: 0px !important; margin-top: 0px !important;">
                <input type="file" class="custom-file-input toolbar-inputField" id="contentEditorBoard_toolbar_contentEditorBackgroundImage_<?php echo $contentEditorId; ?>" name="contentEditorBoard_toolbar_contentEditorBackgroundImage">
                <label class="ajaxCallerLink custom-file-label" for="customFile"><?php echo trans('upload.image'); ?></label>
            </div>
<?php else: ?>
            <div class="contentEditorToolbar-input-xsTitle"><b><?php echo trans('upload.image'); ?></b></div>
            <div class="custom-file mt-3 mb-12 toolbar-input" style="padding-top: 0px !important; margin-top: 0px !important;">
                <input type="file" class="custom-file-input toolbar-inputField" id="contentEditorBoard_toolbar_contentEditorBackgroundImage_<?php echo $contentEditorId; ?>" name="contentEditorBoard_toolbar_contentEditorBackgroundImage">
                <label class="ajaxCallerLink custom-file-label" for="customFile"><?php echo trans('upload.image'); ?></label>
            </div>
<?php endif; ?>
        </div>
    </div>
</form>
<?php
// dump($contentEditor);
?>


<!-- <div class="row" style="width: 100%;">

    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 toolbar-inputContainer">
        <div class="input-group toolbar-input">
            <button onclick="ContentEditorToolbar_<?php echo $contentEditorId; ?>.addContentEditorUnit(event);" id="contentEditorBoard_toolbar_addContentEditorUnit" type="button" class="btn btn-success" style="height: 42px; width: 100%; padding-bottom: 20px !important;"><?php echo trans('add.content.assembler.text'); ?></button>
        </div>
    </div>

</div> -->


<div class="contentEditorToolbar-unitCase-rail" style="border: 1px solid #c0c0c0;">
<?php if (count($contentEditor->getContentEditorUnitCase()) == 0): ?>
    <div style="padding-bottom: 10px; padding-left: 10px;"><?php echo trans('no.content.assembler.unit.containers.yet'); ?>
    </div>
<?php else: ?>

    <div id="contentEditorUnit_unitCases_sortableContainer_<?php echo $contentEditorId; ?>" class="ui-sortable sortable sortable-<?php echo $contentEditorId; ?>">
    <?php 
    include('editorToolbarCaseList.php'); 
    ?>
    </div>
    
<?php endif; ?>
</div>
<script>
$( "#contentEditorUnit_unitCases_sortableContainer_<?php echo $contentEditorId; ?>" ).sortable({
    create: function(event, ui) {
        // console.log(event.type);
        if (event.type != 'sortcreate') {
            ContentEditorSorter_<?php echo $contentEditorId; ?>.sortContentEditorUnitCases();
        }
    },
    stop: function(event, ui) {
        ContentEditorSorter_<?php echo $contentEditorId; ?>.sortContentEditorUnitCases();
    }
});
</script>