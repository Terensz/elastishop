<!DOCTYPE html>
<?php $httpDomain = $container->getUrl()->getHttpDomain(); ?>
<html lang="en-US">
<head>
<title><?php echo trans($container->getRouting()->getPageRoute()->getTitle()); ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="<!--keywords-->">
<meta name="description" content="<!--description-->">
<link rel="icon" href="<?php echo $httpDomain; ?>/accessory/favicon" type="image/x-icon">
<style>
</style>
<script type="text/javascript">
</script>
<script src="/public_folder/plugin/jQuery/3.3.1/jquery-3.3.1.min.js"></script>
<link href="/public_folder/plugin/Bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<script src="/public_folder/plugin/Bootstrap/4.1.3/js/bootstrap.min.js"></script>
<link href="/public_folder/skin/Basic/css/skin.css" rel="stylesheet">
<link href="/public_folder/asset/LoadingHandler/loadingHandlerSpinner.css" rel="stylesheet">
<script src="/public_folder/asset/LoadingHandler/LoadingHandler.js"></script>
<script src="/elastitools/js"></script>
<script>
</script>
</head>
<body>
<noscript>
	This page needs JavaScript activated to work.
	<style>#documentBody { display:none; }</style>
</noscript>
<div id="documentBody">
	<div id="documentBackground" style="touch-action: none;"></div>
	<div id="structure">
		{{ structure }}
	</div>
</div>
<!-- ConfirmModal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="editorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="confirmModalBody" class="modal-body"></div>
      <div class="modal-footer">
        <button id="confirmModalClose" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo trans('close'); ?></button>
        <button id="confirmModalConfirm" type="button" class="btn btn-primary"><?php echo trans('confirm'); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- /ConfirmModal -->
<!-- EditorModal -->
<div class="modal fade" id="editorModal" tabindex="-1" role="dialog" aria-labelledby="editorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editorModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="editorModalBody" class="modal-body"></div>
      <!-- <div class="modal-footer"> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo trans('close'); ?></button> -->
        <!-- <button type="button" class="btn btn-primary"><?php echo trans('save.changes'); ?></button> -->
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- /EditorModal -->
</body>
</html>
