<?php  
if (App::getContainer()->getSession()->get('site_adminViewState')):
?>
<div id="AAW_editor_<?php echo $article->getId(); ?>">
<?php echo $editorContent; ?>
</div>
<?php
endif;
?>
<div id="AAW_viewerContainer_<?php echo $article->getId(); ?>">
<?php 
include($wrapped ? 'wrappedViewerContainer.php' : 'viewerContainer.php');
?>
</div>