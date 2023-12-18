<?php 
if ($grantedViewProjectAdminContent && App::getContainer()->getSession()->get('site_adminViewState')):
    include('editor.php');
else:
    echo $viewerView;
?>
<script>
    $('document').ready(function() {
        $('.editorArea').html('');
        $('.contentViewer-unit').off('mousedown');
        $('body').off('mouseover, mouseup', '.contentViewer-unit-container');
        $('body').off('mouseover mousedown', '.contentViewer-unit-container');
        $('body').off('dblclick', '.contentViewer-unit-container');
        if (typeof(EditorDragger_<?php echo $contentEditorId; ?>) != 'undefined') {
            EditorDragger_<?php echo $contentEditorId; ?> = null;
        }
    });
</script>
<?php
endif; 
?>