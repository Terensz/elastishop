<!DOCTYPE html>
<?php $httpDomain = $container->getUrl()->getHttpDomain(); ?>
<html lang="en-US">
<head>
<title><?php echo $container->getRouting()->getPageRoute()->getTitle(); ?></title>
<meta charset="UTF-8">
<!-- <link rel="dns-prefetch" href="<?php echo $container->getUrl()->getHttpDomain(); ?>"> -->
<meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="<!--keywords-->">
<meta name="description" content="<!--description-->">
<meta property="og:title" content="<?php echo $container->getOpenGraphData()['title']; ?>" />
<meta property="og:description" content="<?php echo $container->getOpenGraphData()['description']; ?>" />
<meta property="og:type" content="<?php echo $container->getOpenGraphData()['type']; ?>"/>
<meta property="og:image" content="<?php echo $container->getOpenGraphData()['image']; ?>" />
<meta property="og:url" content="<?php echo $container->getOpenGraphData()['url']; ?>" />
<meta property="og:site_name" content="<?php echo $container->getOpenGraphData()['site_name']; ?>" />
<meta property="og:locale" content="<?php echo $container->getOpenGraphData()['locale']; ?>" />
<meta property="og:updated_time" content="<?php echo $container->getOpenGraphData()['updated_time']; ?>" />
<link rel="icon" id="favicon" href="/accessory/favicon/<?php echo mt_rand(); ?>" type="image/x-icon">
<script src="/dynamicSkeleton/scripts/head"></script>
<div id="cp-scriptContainer">
<?php 
if ($container->isGranted('viewSiteHelperContent')) {
	include('framework/packages/FrameworkPackage/view/widget/controlPanel/scripts/siteHelpersScripts.php');
} else {
	include('framework/packages/FrameworkPackage/view/widget/controlPanel/scripts/visitorsScripts.php');
}
?>
</div>
<?php  
$autoScripts = $container->searchFileMap(['classType' => 'autoScript']);
?>
<?php if (!empty($autoScripts) && is_array($autoScripts)): ?>
	<?php foreach ($autoScripts as $autoScript): ?>
		<?php  
		// dump($autoScript);

		$container->wireService($autoScript['path'].'/'.$autoScript['fileName']);
		$controllerNamespace = $autoScript['namespace'];
		if (defined($controllerNamespace.'::'.'AUTO_SCRIPT_CONFIG')) {
			$config = $controllerNamespace::AUTO_SCRIPT_CONFIG;
			if ($config['location'] == 'head' && $config['active'] == true) {
				$controller = new $controllerNamespace();
				echo $controller->createScript();
			}
		}
		?>
	<?php endforeach; ?>
<?php endif; ?>
<div id="pageStyle-container">
<?php
include('framework/packages/FrameworkPackage/view/style/pageStyle.php');
?>
</div>
<?php
include('framework/packages/FrameworkPackage/view/scriptLoader/basicScriptLoader.php');
?>
<?php
# Egyelore ez az adat nem jon at, nem foglalkozunk vele. Ki is van kapcsolva a RouteRendering-ben.
if (isset($customStyleSheets) && $customStyleSheets != array()):
	foreach ($customStyleSheets as $customStyleSheet):
?>
	<link id="<?php echo $customStyleSheet['id']; ?>" href="<?php echo $httpDomain; ?>/public_folder/skin/<?php echo $skinName; ?>/css/custom/<?php echo $customStyleSheet['fileName']; ?>" rel="stylesheet">
<?php
	endforeach;
endif;

?>
<script src="/elastitools/js"></script>
<link rel="stylesheet" href="/dynamicSkeleton/styleSheet">
</head>

<body>
<div id="cookieBox-container"></div>
<div id="cp-container"></div>
<noscript>
	This page needs JavaScript activated to work.
	<style>#documentBody { display:none; }</style>
</noscript>

	<!-- ConfirmModal -->
	<div class="modal fade" id="confirmModal" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="confirmModalLabel"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="confirmModalBody"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo trans('close'); ?></button>
				<button id="confirmModalConfirm" type="button" class="btn btn-primary"><?php echo trans('confirm'); ?></button>
			</div>
			</div>
		</div>
	</div>
	<!-- /ConfirmModal -->

	<!-- EditorModal -->
	<!-- <div class="modal fade" id="editorModal" tabindex="-1" role="dialog" aria-labelledby="editorModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editorModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="editorModalBody" class="modal-body"></div>
			</div>
		</div>
	</div> -->

	<div class="modal fade" id="editorModal" tabindex="-1">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editorModalLabel"></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="editorModalBody"></div>
			</div>
		</div>
	</div>

	<!-- smallModal -->
	<div class="modal fade" id="smallModal" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="smallModalLabel"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="smallModalBody"></div>
				<div class="modal-footer">
					<button id="smallModalConfirm" type="button" class="btn btn-primary"><?php echo trans('save'); ?></button>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="defaultLightbox d-none d-md-block" id="defaultLightbox" style="right: 0px; display: none !important;">
		<div class="defaultLightbox-title d-flex justify-content-between align-items-center p-3" onclick="Lightbox.hide(event)">
			<div class="weight-800 pxl-16 letter-spacing text-white p-0">Audi - Future is an Attitude</div>
			<a href="" class="text-white pointer" title="Bezár">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="19.801" x="0px" y="0px" viewBox="0 0 19.8 19.8" style="enable-background:new 0 0 19.8 19.8;" xml:space="preserve">
					<g id="menu_close" transform="translate(-3.101 0.186)">
						<line id="Line_142" style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;" x1="4.5" y1="18.2" x2="21.5" y2="1.2"></line>
						<line id="Line_143" style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;" x1="4.5" y1="1.2" x2="21.5" y2="18.2"></line>
					</g>
				</svg>
			</a>
		</div>
		<iframe id="defaultLightbox-iframe" width="560" height="315" src="https://www.youtube.com/embed/pWaHZ2oRE7s" frameborder="0" allowfullscreen></iframe>
	</div> -->

	<div class="defaultLightbox d-none d-md-block" id="defaultLightbox" style="right: 0px; display: none !important;">
		<div class="defaultLightbox-title d-flex justify-content-between align-items-center p-3">
			<div class="weight-800 pxl-16 letter-spacing text-white p-0">Audi - Future is an Attitude</div>
			<div class="chevron-icons">
				<!-- <a href="#" class="text-white pointer" title="Felfelé mutató chevron">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
						<polygon points="2.5,14.8 10,7.3 17.5,14.8 18.5,13.8 10,5.3 1.5,13.8 "></polygon>
					</svg>
				</a>
				<a href="" class="text-white pointer" title="Lefelé mutató chevron">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
						<polygon points="18.5,5.2 10,12.7 1.5,5.2 0.5,6.2 10,14.7 18.5,6.2 "></polygon>
					</svg>
				</a>
				<a href="" class="text-white pointer" title="Felfelé mutató chevron">
					<svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
						<polygon points="2.5,14.8 10,7.3 17.5,14.8 18.5,13.8 10,5.3 1.5,13.8 "></polygon>
					</svg>
				</a>
				<a href="" class="text-white pointer" title="Lefelé mutató chevron">
					<svg xmlns="http://www.w3.org/2000/svg" fill="#ffffff" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
						<polygon points="18.5,5.2 10,12.7 1.5,5.2 0.5,6.2 10,14.7 18.5,6.2 "></polygon>
					</svg>
				</a> -->
				<a href="" onclick="Lightbox.hide(event);" class="text-white pointer" title="Bezár">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="19.801" x="0px" y="0px" viewBox="0 0 19.8 19.8" style="enable-background:new 0 0 19.8 19.8;" xml:space="preserve">
						<g id="menu_close" transform="translate(-3.101 0.186)">
							<line id="Line_142" style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;" x1="4.5" y1="18.2" x2="21.5" y2="1.2"></line>
							<line id="Line_143" style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;" x1="4.5" y1="1.2" x2="21.5" y2="18.2"></line>
						</g>
					</svg>
				</a>
			</div>
		</div>
		<!-- <iframe id="defaultLightbox-iframe" width="560" height="315" src="https://www.youtube.com/embed/pWaHZ2oRE7s" frameborder="0" allowfullscreen></iframe> -->
	</div>

<div id="documentBody" class="preventTouchScroll">
	<div id="documentBackground" class="preventTouchScroll"></div>
	<div id="structureScripts"><?php echo $container->getWidgetScripts(); ?></div>
	<div id="structure">
		{{ structure }}
	</div>
</div>
<div id="systemTranslations" style="display: none;">
<?php
// dump($container->getSystemTranslations());exit;
foreach ($container->getSystemTranslations() as $systemTranslationKey => $systemTranslationValue) {
	$systemTranslationKey = str_replace('.', '_', $systemTranslationKey);
?>
	<div id="systemTranslation-<?php echo $systemTranslationKey; ?>"><?php echo $systemTranslationValue; ?></div>
<?php
}
?>
</div>

	<!-- EditorModal Bootstrap 5.3.0 -->
	<!-- <div id="editorModal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 id="editorModalLabel" class="modal-title"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="editorModalBody" class="modal-body"></div>
			</div>
		</div>
	</div> -->

	<!-- <div id="toast" class="toast" role="status" aria-live="off" aria-atomic="true" style="position: fixed; top: 0px; left: 0px; z-index: 30000;">
		<div class="toast-header">
			<strong class="mr-auto" style="font-size: 16px;"><span class="toast-title" id="toast-title"></span></strong>
			<small><span id="toast-created"></span></small>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body" id="toast-body" style="font-size: 24px;"></div>
	</div> -->

	<div id="toast" class="toast" role="alert" aria-live="off" aria-atomic="true" style="position: fixed; top: 0px; left: 0px; z-index: 30000;" data-bs-autohide="true">
		<div class="toast-header">
			<!-- <img src="..." class="rounded me-2" alt="..."> -->
			<strong class="me-auto"><span id="toast-title"></span></strong>
			<small class="toast-created"></small>
			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
		<div id="toast-body" class="toast-body"></div>
	</div>

	<!-- /EditorModal -->

	<script src="/public_folder/plugin/Dashkit/assets/js/vendor-all.js"></script>
	<!-- <script src="/public_folder/plugin/Dashkit/assets/js/plugins/bootstrap.min.js"></script> -->
	<script src="/public_folder/plugin/Bootstrap/5.1.3/bootstrap.bundle.min.js"></script>
	<script src="/public_folder/plugin/Dashkit/assets/js/pcoded.js"></script>
	<script src="/public_folder/plugin/Dashkit/assets/js/plugins/feather.min.js"></script>

</body>
<script src="/dynamicSkeleton/scripts/afterBody?v=<?php echo rand(1000000, 999999999); ?>"></script>
<script>

function checkInternetConnection() {
	var online = navigator.onLine;
	return online;
}

var VideoPlayer = {
    videoElement: null,

    initialize: function () {
        this.videoElement = $('#defaultLightbox-iframe')[0];

        // if (this.videoElement) {
		// 	console.log('this.videoElement');
		// 	// $(document).on('keypress', $.proxy(this.handleKeyPress, this));
		// 	$(document).on('keypress', VideoPlayer.handleKeyPress);

        //     // $(document).on('keypress', function(event) {
		// 	// 	console.log(event.keyCode);
		// 	// 	if (event.keyCode === 32) {
		// 	// 		event.preventDefault();
		// 	// 		this.togglePlayPause();
		// 	// 	}
		// 	// });
        // } else {
        //     console.error('Az iframe nincs készen állva.');
        // }
    },

    handleKeyPress: function (event) {
		console.log(event.keyCode);
        if (event.keyCode === 32) {
            event.preventDefault();
            this.togglePlayPause();
        }
    },

    togglePlayPause: function () {
        console.log('togglePlayPause');

        if (this.videoElement) {
            this.videoElement.contentWindow.postMessage('togglePlayPause', '*');
        } else {
            console.error('Az iframe nincs készen állva.');
        }
    }
};

$(document).ready(function() {

    VideoPlayer.initialize();

	// $('body').keypress(function(event) {
	// 	// console.log('keypress!!!');
	// });

	// document.addEventListener('keydown', function (event) {
	// 	if (event.key === ' ') {
	// 		console.log('Space lenyomva');
	// 	}
	// });

    // document.addEventListener('keydown', function (e) {
    //     VideoPlayer.handleKeyPress(e);
    // });
	// $('body').on('change', '#cookieNoticeVeil', function() {
	// 	CookieInterface.call();
	// });

	CookieInterface.call(false);

	CP.load();

	$('body').off('click', '.doNotTriggerHref');
    $('body').on('click', '.doNotTriggerHref', function(e) {
        e.preventDefault();
    });

	window.addEventListener('keydown', function(e) {
		if(e.keyCode == 32 && e.target == document.body) {
			e.preventDefault();
		}
	});

	$('body').off('mouseover', '.wordExplanation');
	$('body').on('mouseover', '.wordExplanation', function(e) {
		var content = WordExplanation.getContent(e.target.innerHTML);
		$(this).popover({'content': content, 'html': true}).popover('show');
	});

	$('body').off('mouseout', '.wordExplanation, .popover');
	$('body').on('mouseout', '.wordExplanation, .popover', function(e) {
		if ($('.popover').is(':hover') == false) {
        	$('.popover').remove();
    	}
	});

	$('body').off('click');
	$('body').on('click', function(e) {
		if (e.target.className != 'wordExplanation' && e.target.className != 'popover-body') {
			$('.popover').remove();
		}
	});

	$('body').off('click', '#confirmModalClose');
	$('body').on('click', '#confirmModalClose', function() {
		$('#confirmModalConfirm').attr('onClick', '');
		$('#confirmModalBody').html('<?php echo trans('are.you.sure'); ?>');
	});

	$('body').css('background-color', '<?php echo $backgroundColor; ?>');
	Structure.setPageSwitchBehavior({<?php
	$i = 0;
	foreach ($container->getRouting()->getActualRoute()->getPageSwitchBehavior() as $widgetName => $widgetBehavior) {
		echo $i > 0 ? ',' : '';
		echo $widgetName.": '".$widgetBehavior."'";
		$i++;
	}
	?>});

	Structure.setWidgetChanges({<?php
	$i = 0;
	foreach ($container->getRouting()->getActualRoute()->getWidgetChanges() as $widgetPos => $widgetPath) {
		echo $i > 0 ? ',' : '';
		echo $widgetPos.": '".$widgetPath."'";
		$i++;
	}
	?>});

	$('#editorModal').off('hide.bs.modal');
	$('#editorModal').on('hide.bs.modal', function () {
		$('body').css('overflow', 'visible');
		$('#editorModalLabel').html('');
		$('#editorModalBody').html('');

		// $('body').css('position', 'static');
		// $('body').css('overflow-y', 'auto');
		// $('body').css('overflow-clip-margin', '0px');
		$('#editorModal').removeAttr('data-target');
        $('#editorModal').removeAttr('data-backdrop');
        $('#editorModal').removeAttr('data-keyboard');
		if (typeof(CKEDITOR) != 'undefined') {
			// console.log(CKEDITOR.instances);
			// ckeditor.instances.body.destroy();
			for (const [key, instance] of Object.entries(CKEDITOR.instances)) {
				instance.destroy();
			}
		}
	    ElastiTools.removeVeil();
	});

	$('#editorModal').off('show.bs.modal');
	$('#editorModal').on('show.bs.modal', function () {
		// $('body').css('overflow', 'hidden');

		// $('body').css('position', 'fixed');
		// $('body').css('overflow-y', 'scroll');
		// $('body').css('overflow-clip-margin', '20px');
		// document.body.style.overflow = "clip";
		// overflow-clip-margin: 20px;
	});
	$('#editorModal').off('hidden.bs.modal');
	$('#editorModal').on('hidden.bs.modal', function () {
		// $('body').css('overflow', 'scroll');

		// $('body').css('position', 'fixed');
		// $('body').css('overflow-y', 'scroll');
		// $('body').css('overflow-clip-margin', '20px');
		// document.body.style.overflow = "clip";
		// overflow-clip-margin: 20px;
	});

	$('body').data('structureName', '<?php echo framework\kernel\utility\BasicUtils::explodeAndGetElement($container->getRouting()->getPageRoute()->getStructure(), '/', 'last'); ?>');
	// $('body').data('structureName', '<?php echo ''; ?>');
	$('body').data('backgroundEngine', '<?php echo $container->getRouting()->getPageRoute()->getBackgroundEngine(); ?>');
	$('body').data('backgroundTheme', '<?php echo $container->getRouting()->getPageRoute()->getBackgroundTheme(); ?>');
	$('body').data('skinName', '<?php echo $skinName; ?>');

    $('#documentBody').css('display', 'inline');

	if ($('body').data('backgroundEngine') != 'none') {
		Background.call($('body').data('backgroundEngine'), $('body').data('backgroundTheme'));
	}

	$('body').off('click', '.ajaxCaller');
	$('body').on('click', '.ajaxCaller', function (e) {
		e.preventDefault();
		$('#editorModal').modal('hide');
		Structure.handlePageSwitchBehavior();
		Structure.call($(this).parent().attr('href'));
	});

	$('body').off('click', '.elastiSiteAjaxCallerLink');
	$('body').on('click', '.elastiSiteAjaxCallerLink', function (e) {
		var esHome = '<?php echo rtrim($container->getConfig()->getGlobal('website.ElastiSiteHomepageLink'), '/'); ?>';
		var thisHome = '<?php echo rtrim($container->getUrl()->getHttpDomain(), '/'); ?>';
		if (esHome != thisHome) {
			return;
		}
		e.preventDefault();
		$('#editorModal').modal('hide');
		Structure.handlePageSwitchBehavior();
		var link = $(this)[0];
		Structure.call(link.href);
	});

	// console.log('ajaxCallerLink init');
	$('body').off('click', '.ajaxCallerLink');
	$('body').on('click', '.ajaxCallerLink', function (e) {
		// console.log('ajaxCallerLink!!!');
		e.preventDefault();
		$('#editorModal').modal('hide');
		Structure.handlePageSwitchBehavior();
		var link = $(this)[0];
		Structure.call(link.href);
	});

	$('.ajaxCallerLink').off('click');
	$('.ajaxCallerLink').on('click', function(e) {
		// console.log(e);
		// console.log('ajaxCallerLink 2 !!!');
		e.preventDefault();
		$('#editorModal').modal('hide');
		Structure.handlePageSwitchBehavior();
		var link = $(this)[0];
		Structure.call(link.href);
	});

	$('body').off('click', '.divLink');
	$('body').on('click', '.divLink', function (e) {
		e.preventDefault();
		$('#editorModal').modal('hide');
	});

	$('body').off('click', '.pseudoLink');
	$('body').on('click', '.pseudoLink', function (e) {
		e.preventDefault();
		$('#editorModal').modal('hide');
	});

	$('body').off('click', '.buttonLink');
	$('body').on('click', '.buttonLink', function (e) {
	    e.preventDefault();
		$('#editorModal').modal('hide');
	});

	window.onpopstate = history.onpushstate = function(e) {
		console.log(window.location.href);
		Structure.call(window.location.href, false, false);
	}

	$('body').off('keypress', '.enterSubmits');
	$('body').on('keypress', '.enterSubmits', function(e) {
		if (e.keyCode === 13) {
			var id = $(this).attr('id');
			var inputParts = id.split('_');
			if (inputParts.length > 1) {
				var submitId = '';
				for (var i = 0; i < inputParts.length; i++) {
					if (i > 0) {
						submitId += '_';
					}
					if (i == (inputParts.length - 1)) {
						submitId += 'submit';
					} else {
						submitId += inputParts[i];
					}
				}
				$('#' + submitId).click();
				e.preventDefault();
			}
		}
	});

    $(window).scroll(function () {
        // stickyMenuListener();
		// stickyFooterListener();
    });

	Structure.loadSkin('<?php echo App::getContainer()->getSkinData('skinName'); ?>');
});


</script>
</html>
