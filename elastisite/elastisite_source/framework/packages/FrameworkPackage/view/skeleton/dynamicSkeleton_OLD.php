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
<link rel="icon" id="favicon" href="/favicon/<?php echo mt_rand(); ?>" type="image/x-icon">
<script type="text/javascript">
var htmlEditorConfig = {
	iconsPath: "<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/plugin/nicEdit/nicEditIcons-latest.gif",
    imageUploadUrl: "<?php echo $container->getUrl()->getHttpDomain(); ?>/upload/image"
};
</script>
<!--ALMA!!!! <?php echo $skinName; ?>-->
<?php
include('framework/packages/FrameworkPackage/view/style/sheet.php');
?>
<!--ALMA2!!!!-->
<?php
include('framework/packages/FrameworkPackage/view/scriptLoader/basicScriptLoader.php');
?>
<!--ALMA3!!!!-->
<?php
# Egyelore ez az adat nem jon at, nem foglalkozunk vele. Ki is van kapcsolva a RouteRendering-ben.
if (isset($customStyleSheets) && $customStyleSheets != array()) {
	foreach ($customStyleSheets as $customStyleSheet) {
?>
	<link id="<?php echo $customStyleSheet['id']; ?>" href="<?php echo $httpDomain; ?>/public_folder/skin/<?php echo $skinName; ?>/css/custom/<?php echo $customStyleSheet['fileName']; ?>" rel="stylesheet">
<?php
	}
}

?>
<script src="/elastitools/js"></script>

<style>
body {
    width: 100%;
}

#documentBody {
    max-width: 98%;
}

body.modal-open {
    overflow: scroll;
}

.sticky-menu {
  position: fixed;
  top: 0;
  /* width: 1200px; */
}

.sticky-footer {
  position: fixed;
  bottom: 0;
  /* width: 1200px; */
}

#backgroundWrapper {
    scroll-behavior: smooth;
}

/* #sheetContainer {
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  z-index: 4;
} */

</style>
</head>
<body>
<noscript>
	This page needs JavaScript activated to work.
	<style>#documentBody { display:none; }</style>
</noscript>
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
<!-- ConfirmModal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="confirmModalBody" class="modal-body"><?php echo trans('are.you.sure'); ?></div>
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
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
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

<div id="toast" class="toast" role="status" aria-live="off" aria-atomic="true" style="position: fixed; top: 0px; left: 0px; z-index: 3000000000;">
	<div class="toast-header">
		<!-- <img src="..." class="rounded mr-2" alt="..."> -->
		<strong class="mr-auto" style="font-size: 16px;"><span class="toast-title" id="toast-title"></span></strong>
		<small><span id="toast-created"></span></small>
		<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="toast-body" id="toast-body" style="font-size: 24px;"></div>
</div>
<!-- /EditorModal -->
<style>
	/* #videoBox {
		display: none;
		position: fixed;
		top: 20px;
		right: 0%;
		width: 320px;
		height: 212px;
		background-color: #fff;
		z-index: 230;
		box-shadow: 2px 2px 2px #2d3849;
	}

	.videoBoxButton {
		width: 200px;
		height:40px;
		background-color: #3462ae;
		color: #fff;
		box-shadow: 1px 1px 1px #667896;
	} */

	.window-button {
		width: 32px;
		height: 32px;
		float: right;
		border: 1px solid #c0c0c0;
		background-color: #fff;
	}
</style>
<!-- <div class="lightOpacity" id="videoBox"></div> -->
<!-- <script src="<?php echo $httpDomain; ?>/public_folder/plugin/Skrollr/skrollr.js"></script>
<script type="text/javascript">
	var s = skrollr.init();
</script> -->
</body>
<script>
function dragElement(elmnt) {
	var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
	if (document.getElementById(elmnt.id + "header")) {
		document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
	} else {
		elmnt.onmousedown = dragMouseDown;
	}

	function dragMouseDown(e) {
		e = e || window.event;
		e.preventDefault();
		pos3 = e.clientX;
		pos4 = e.clientY;
		document.onmouseup = closeDragElement;
		document.onmousemove = elementDrag;
	}

	function elementDrag(e) {
		e = e || window.event;
		e.preventDefault();
		pos1 = pos3 - e.clientX;
		pos2 = pos4 - e.clientY;
		pos3 = e.clientX;
		pos4 = e.clientY;
		elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
		elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
	}

	function closeDragElement() {
		document.onmouseup = null;
		document.onmousemove = null;
	}
}
var ExitIntent = {
	addEvent: function(obj, evt, fn) {
		if (obj.addEventListener) {
			obj.addEventListener(evt, fn, false);
		}
		else if (obj.attachEvent) {
			obj.attachEvent("on" + evt, fn);
		}
	},
	startPopup: function() {
		console.log('ExitIntent startPopup');
	}
};
var WordExplanation = {
	getContent: function(keyText) {
		var content = '';
		$.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/ajax/wordExplanation',
            'data': {'keyText': keyText},
            'async': true,
            'success': function(response) {
				// console.log(response.view);
                content = response.view;
            },
            'error': function(request, error) {
                console.log(request);
                console.log(" Can't do because: " + error);
            }
		});
		return content;
	}
};
var FormValidator = {
	displayErrors: function(formSelector, messages) {
		// console.log('FormValidator.displayErrors');
		// console.log(messages);
        var inputs = $(formSelector).find('.inputField');
		// console.log('inputs!', inputs);
        for (var i = 0; i < inputs.length; i++) {
			// console.log('inputs[' + i + ']', inputs[i]);
            var id = $(inputs[i]).attr('id');
			var errorDivId = id + '-error';
			// var temp1 = id.split('[');
			// var temp2 = temp1[0].split('_');
			// var attribute = temp2[2];
			// var errorDivId = temp2[0] + '_' + temp2[1] + '_' + attribute + '-error';
			// console.log('messages[' + id + ']', messages[id]);
			if (messages[id] != null) {
				// console.log('bent!');
				if ($('#' + errorDivId).attr('id') != undefined) {
					$('#' + errorDivId).remove();
				}
				var message = '<div id="' + errorDivId + '" class="fieldError text-danger">' + messages[id] + '</div>';
				$(inputs[i]).parent('.form-group').append(message);
			} else {
				$('#' + errorDivId).remove();
			}
        }
    }
};
// var Favicon = {
// 	show: function() {
// 		console.log('<?php echo $httpDomain; ?>/favicon');
// 		$('#favicon').attr('href', '<?php echo $httpDomain; ?>/favicon/' + Math.random());
// 	},
// 	reload: function() {
// 		Favicon.show();
// 		// const delay = ms => new Promise(res => setTimeout(res, ms));
// 		// const delayFaviconReload = async () => {
// 		// 	await delay(3000);
// 		// 	Favicon.show();
// 		// };
// 	}
// };
var Background = {
	call: function(backgroundEngine, backgroundTheme) {
		var url = '<?php echo $container->getUrl()->getHttpDomain(); ?>/background/' + backgroundEngine + '/' + backgroundTheme;
		// console.log(url);
		$.ajax({
	        'type' : 'POST',
	        'url' : url,
	        'data': {},
	        'async': true,
			'success': function(response) {
				// console.log(response);
				ElastiTools.checkResponse(response);
				$('#documentBackground').html(response.view);
	        },
	        'error': function(request, error) {
				ElastiTools.checkResponse(request.responseText);
	            // console.log(request);
	            console.log(" Can't do because: " + error);
	        },
	    });
	},
	onStructureChange: function(oldEngine, newEngine, oldTheme, newTheme) {
		$('body').data('backgroundEngine', newEngine);
		$('body').data('backgroundTheme', newTheme);
		// if (oldEngine != newEngine && oldTheme != newTheme) {
		// 	Background.call(newEngine, newTheme);
		// }
		Background.call(newEngine, newTheme);
	}
};
var Structure = {
	changed: false,
	setBackgroundTheme: function(backgroundTheme) {
		if (backgroundTheme !== null && backgroundTheme !== undefined) {
			$('body').data('backgroundTheme', backgroundTheme);
		} else {
			$('body').data('backgroundTheme', 'general');
		}
		if (typeof(BackgroundEngine) == 'object') {
			BackgroundEngine.set($('body').data('backgroundTheme'));
		}
	},
	removeBackgroundTheme: function() {
		if (typeof(BackgroundEngine) == 'object') {
			BackgroundEngine.remove($('body').data('backgroundTheme'));
		}
	},
	call: function(url, forceReload, pushUrlToHistory) {
		pushUrlToHistory = (typeof pushUrlToHistory === 'undefined') ? true : pushUrlToHistory;
		// console.log('Structure.call');
		$("#editorModal").unbind("hidden.bs.modal");
		$('#editorModalLabel').html('');
		$('#editorModalBody').html('');
		LoadingHandler.start();
		$('.daterangepicker').remove();
		url = (typeof url !== 'undefined') ? url : window.location;
		if (pushUrlToHistory) {
			window.history.pushState("object or string", "Title", url);
		}
		
		forceReload = (typeof forceReload !== 'undefined') ?  forceReload : false;
		Structure.removeBackgroundTheme();
		// console.log(url);
	    $.ajax({
	        'type' : 'POST',
	        'url' : url,
	        'data': {},
	        'async': true,
			'success': function(response) {
				// console.log(response.data);
				ElastiTools.checkResponse(response);
				// window.history.pushState("object or string", "Title", url);
				document.title = response.data['title'];
				Structure.setBackgroundTheme(response.data['backgroundTheme']);
				Structure.setPageSwitchBehavior(response.data['pageSwitchBehavior']);
				Structure.setWidgetChanges(response.data['widgetChanges']);

				// console.log('Current structure: ' + $('body').data('structureName'));
				// console.log('New structure: ' + response.data['structureName']);
				// console.log('widgetChanges: ' + response.data['widgetChanges']);

                if ($('body').data('structureName') == response.data['structureName']) {
                    Structure.changed = false;
                } else {
                    Structure.changed = true;
                }
				// console.log();
				// if (response.data['widgetChanges'] !== false && Structure.changed == false) {
				// if (Structure.changed == false) {
				if (response.data['widgetChanges'] !== false && Structure.changed == false) {
					// console.log('response.data[cwidg]', response.data['widgetChanges']);
					Structure.update(response.view);
					$('#structureScripts').html(response.data['widgetScripts']);
				} else {
					if ($('body').data('structureName') == response.data['structureName'] || forceReload == true) {
						// console.log('Reload');
						if ($('#structure').html() == '' || forceReload == true) {
							$('#structure').html(response.view);
						} else {
							Structure.update(response.view);
						}
						$('#structureScripts').html(response.data['widgetScripts']);
					} else {
						// console.log('Fade');
						$('#structure').fadeOut(0, function(){
							$('#structure').html(response.view);
							$('#structure').fadeIn(0);
							$('#structureScripts').html(response.data['widgetScripts']);
						});
					}
				}
				$('body').css('background-color', '#' + response.data['backgroundColor']);
				if ($('body').data('structureName') != response.data['structureName'] 
					|| $('body').data('skinName') != response.data['skinName']) {
					let skinCssContainerContent = '\
					<link href="<?php echo $container->getUrl()->getHttpDomain(); ?>/public_folder/skin/' + response.data['skinName'] + '/css/skin.css?v=<?php echo time(); ?>" rel="stylesheet">\
					';
					// console.log(skinCssContainerContent);
					$('#skinCssContainer').html(skinCssContainerContent);
				}
				$('body').data('structureName', response.data['structureName']);
				$('body').data('skinName', response.data['skinName']);
				// $('body').data('backgroundColor', response.data['backgroundColor']);
				// console.log('structure kesz, johetnek a scriptek');

				Background.onStructureChange(
					$('body').data('backgroundEngine'),
					response.data['backgroundEngine'],
					$('body').data('backgroundTheme'),
					response.data['backgroundTheme']
				);
				Structure.stopLoadingHandlerWhenAjaxFinished();
	        },
	        'error': function(request, error) {
				ElastiTools.checkResponse(request.responseText);
	        },
	    });
	},
	pageSwitchBehavior: {},
	setPageSwitchBehavior: function(rawPageSwitchBehavior) {
		Structure.pageSwitchBehavior = rawPageSwitchBehavior;
	},
	getPageSwitchBehavior: function(widgetName) {
		for (let [key, value] of Object.entries(Structure.pageSwitchBehavior)) {
			if (key == widgetName) {
				return value;
			}
		}
		return 'refresh';
	},
	widgetChangesChecker: [],
	widgetChanges: {},
	setWidgetChanges: function(rawWidgetChanges) {
		// console.log(rawWidgetChanges);
		for (let [key, value] of Object.entries(rawWidgetChanges)) {
			var checker = Structure.widgetChangesChecker;
			checker.push(key);
			var valueParts = value.split('/');
			var thisWidgetName = valueParts[valueParts.length - 1];
			Structure.widgetChanges[key] = thisWidgetName;
		}
	},
	// update_OLD: function() {
	// 	var widgetContainers = $('#structure').find('.widgetContainer');
	// 	for (var i = 0; i < widgetContainers.length; i++) {
	// 		var id = $(widgetContainers[i]).attr('id');
	// 		var widgetName = id.replace('widgetContainer-', '');
	// 		eval(widgetName + ".call()");
	// 	}
	// },
	getWidgetName: function(widgetContainerIdentifier) {
		for (let [key, value] of Object.entries(Structure.widgetChanges)) {
			if (key == widgetContainerIdentifier) {
				return value;
			}
		}
		return widgetContainerIdentifier;
	},
	getWidgetContainerIdentifier: function(widgetName) {
		for (let [key, value] of Object.entries(Structure.widgetChanges)) {
			if (value == widgetName) {
				return key;
			}
		}
		return widgetName;
	},
	update: function(structureView) {
		// return true;
		// structureView = typeof structureView === 'undefined' ? null : structureView;
		// if (structureView !== null) {
		// 	$('#structureScripts').html($(structureView).filter('#structureScripts'));
		// 	return true;
		// }
		//
		// var widgetContainers = $('#structure').find('.widgetContainer');
		// for (var i = 0; i < widgetContainers.length; i++) {
		// 	var id = $(widgetContainers[i]).attr('id');
		// 	var widgetContainerIdentifier = id.replace('widgetContainer-', '');
		// 	var widgetName = Structure.getWidgetName(widgetContainerIdentifier);
		// 	eval(widgetName + ".call()");
		// }
	},
	handlePageSwitchBehavior: function() {
		var widgetContainers = $('.widgetContainer');
		for (var i = 0; i < widgetContainers.length; i++) {
			var id = $(widgetContainers[i]).attr('id');
			var widgetContainerIdentifier = id.replace('widgetContainer-', '');
			var widgetName = Structure.getWidgetName(widgetContainerIdentifier);
			var pageSwitchBehavior = Structure.getPageSwitchBehavior(widgetName);
			if (pageSwitchBehavior == 'restore') {
				var dataId = 'widgetContent-' + widgetName;
				var saved = Structure.valsToValues(widgetName);
				if (saved === true) {
					// Structure.throwSystemToast({title: 'system.message', body: 'page.data.stored'});
				}
				$('body').data(dataId, $('#' + id).html());
			}
		}
	},
	loadWidget: function(widgetName) {
		LoadingHandler.start();
		var pageSwitchBehavior = Structure.getPageSwitchBehavior(widgetName);
        var widgetContainerIdentifier = Structure.getWidgetContainerIdentifier(widgetName);
        var dataId = 'widgetContent-' + widgetName;
		if (pageSwitchBehavior == 'keep' && Structure.changed === true) {
			pageSwitchBehavior = 'refresh';
		}
		if (pageSwitchBehavior == 'refresh') {
			eval(widgetName + ".call()");
			Structure.stopLoadingHandlerWhenAjaxFinished();
			return true;
		}
		if (pageSwitchBehavior == 'restore') {
			if (typeof($('body').data(dataId)) != 'undefined' && $('body').data(dataId) !== null) {
				$('#widgetContainer-' + widgetContainerIdentifier).html($('body').data(dataId));
				$('body').data(dataId, null);
			} else {
				eval(widgetName + ".call()");
			}
		}
		// LoadingHandler.stop();
	},
	stopLoadingHandlerWhenAjaxFinished: function() {
		$(document).ajaxStop(function(){
			$(this).unbind("ajaxStop");
			LoadingHandler.stop();
		});
	},
	getSystemTranslation: function(key) {
		var keyArray = key.split('.');
		key = keyArray.join('_');
		return $('#systemTranslation-' + key).html();
	},
	throwSystemToast: function(obj) {
		$('#toast-title').html(Structure.getSystemTranslation(obj['title']));
		$('#toast-body').html(Structure.getSystemTranslation(obj['body']));
		$('#toast').toast({delay: 3000});
		$('#toast').toast('show');
	},
	throwToast: function(title, body) {
		$('#toast-body').removeClass('toast-error');
		$('#toast-body').addClass('toast-success');
		$('#toast-title').html(title);
		$('#toast-body').html(body);
		$('#toast').toast({delay: 3000});
		$('#toast').toast('show');
	},
	throwErrorToast: function(title, body) {
		$('#toast-body').addClass('toast-error');
		$('#toast-body').removeClass('toast-success');
		$('#toast-title').html(title);
		$('#toast-body').html(body);
		$('#toast').toast({delay: 3000});
		$('#toast').toast('show');
	},
	valsToValues: function(widgetName) {
		var saved = false;
		var widgetContainerIdentifier = Structure.getWidgetContainerIdentifier(widgetName);
		var widgetContainerId = 'widgetContainer-' + widgetContainerIdentifier;
		var inputs = $('#' + widgetContainerId + ' :input');
		for (var i = 0; i < inputs.length; i++) {
			if ($(inputs[i]).val() != $(inputs[i]).attr('value')) {
				saved = true
			}
			$(inputs[i]).attr('value', $(inputs[i]).val());
		}
		return saved;
	},
};

var CookieInterface = {
	call: function(submitted) {
		console.log('CookieInterface call');
		var form = $('#cookieNotice_form');
		ajaxData = {'cookieNotice_session_submit': submitted};
        $.ajax({
            'type' : 'POST',
            'url' : '<?php echo $container->getUrl()->getHttpDomain(); ?>/widget/CookieNoticeWidget',
            'data': ajaxData,
            'async': true,
            'success': function(response) {
				console.log(response);
                ElastiTools.checkResponse(response);
				if (response.view === false) {
					$('#cookieNoticeFrame').remove();
					$('#cookieNoticeVeil').remove();
				}
				else {
					$('#documentBody').append(response.view);
				}
            },
            'error': function(request, error) {
                ElastiTools.checkResponse(request.responseText);
            },
        });
    },
};

function stickyMenuListener() {
    var headerPos = $('#stickyMenuStart').position();
    var stickyHeight = $('.stickyMenuDiv').outerHeight();
    var stickyMenuWedge = '<div id="stickyMenuWedge" style="height: ' + stickyHeight + 'px;"></div>';
    if (typeof(headerPos) != 'undefined') {
        var headerFromTop = headerPos['top'];
        var sheetTopPadding = '<?php echo $container->getSkinData('sheetTopPadding'); ?>';
        if (isNaN(sheetTopPadding) === false) {
            headerFromTop = headerFromTop + parseInt(sheetTopPadding);
        }
        if (window.pageYOffset > headerFromTop) {
            $('#stickyMenuWedge').remove();
            $('.stickyMenuDiv').before($(stickyMenuWedge));
            $('.stickyMenuDiv').addClass('sheetWidth');
            $('.stickyMenuDiv').addClass('sticky-menu');
			$('.stickyMenuDiv').css('z-index', 200);
        } else {
            $('#stickyMenuWedge').remove();
            $('.stickyMenuDiv').removeClass('sheetWidth');
            $('.stickyMenuDiv').removeClass('sticky-menu');
			$('.stickyMenuDiv').css('z-index', 220);
        }
    }
}

function stickyFooterListener() {
    // var footerPos = $('#stickyFooterStart').position();
    // var stickyHeight = $('.stickyFooterDiv').outerHeight();
    // var stickyFooterWedge = '<div id="stickyFooterWedge" style="height: ' + stickyHeight + 'px;"></div>';
	// console.log(footerPos);
	// // console.log(stickyHeight);
    // if (typeof(footerPos) != 'undefined') {
	// 	console.log(footerPos);
    //     var headerFromTop = footerPos['top'];
    //     var sheetTopPadding = '<?php echo $container->getSkinData('sheetTopPadding'); ?>';
	// 	console.log(sheetTopPadding);
    //     if (isNaN(sheetTopPadding) === false) {
    //         headerFromTop = headerFromTop + parseInt(sheetTopPadding);
	// 		console.log(headerFromTop);
    //     }
    //     if (window.pageYOffset > headerFromTop) {
    //         $('#stickyFooterWedge').remove();
    //         $('.stickyFooterDiv').before($(stickyFooterWedge));
    //         $('.stickyFooterDiv').addClass('sheetWidth');
    //         $('.stickyFooterDiv').addClass('sticky-footer');
	// 		$('.stickyFooterDiv').css('z-index', 200);
    //     } else {
    //         $('#stickyFooterWedge').remove();
    //         $('.stickyFooterDiv').removeClass('sheetWidth');
    //         $('.stickyFooterDiv').removeClass('sticky-footer');
	// 		$('.stickyFooterDiv').css('z-index', 220);
    //     }
    // }
}

function calculateDistance(elem, mouseX, mouseY) {
	return Math.floor(Math.sqrt(Math.pow(mouseX - (elem.offset().left+(elem.width()/2)), 2) + Math.pow(mouseY - (elem.offset().top+(elem.height()/2)), 2)));
}

$(document).ready(function() {

    $('body').on('click', '.doNotTriggerHref', function(e) {
        e.preventDefault();
    });

	window.addEventListener('keydown', function(e) {
		if(e.keyCode == 32 && e.target == document.body) {
			e.preventDefault();
		}
	});

	// $(document).ajaxStart(function(){
	// 	LoadingHandler.start();
	// });

	// $(document).ajaxStop(function(){
	// 	LoadingHandler.stop();
	// });

	// $('body').on('mouseover', 'alma', function() {
	// 	$('#confirmModalConfirm').attr('onClick', '');
	// });

	// $('.wordExplanation').popover({
	// 	"html": true,
	// 	"content": function(){
	// 		var div_id =  "tmp-id-" + $.now();
	// 		return WordExplanation.popup($(this).attr('href'), div_id);
	// 	}
	// });

	// $('.wordExplanation').hover(function(e) {
	$('body').on('mouseover', '.wordExplanation', function(e) {
        // mX = e.pageX;
        // mY = e.pageY;
        // distance = calculateDistance($(this), mX, mY);
        // console.log(distance);
		content = WordExplanation.getContent(e.target.innerHTML);
		// content = 'alma alma';
		$(this).popover({'content': content, 'html': true}).popover('show');
	});

	$('body').on('mouseout', '.wordExplanation, .popover', function(e) {
        // mX = e.pageX;
        // mY = e.pageY;
        // distance = calculateDistance($(this), mX, mY);
        // console.log(distance);
		if ($('.popover').is(':hover') == false) {
        	$('.popover').remove();
    	}
	});

	// $('body').on('mouseout', '.popover', function(e) {
	// 	if ($('.popover').is(':hover') == false) {
    //     	$('.popover').remove();
    // 	}
	// });

	$('body').on('click', function(e) {
		// console.log(e.target.className);
		if (e.target.className != 'wordExplanation' && e.target.className != 'popover-body') {
			$('.popover').remove();
		}
	});

	// window.onbeforeunload = function() {
    // 	console.log('onbeforeunload');
	// };
	// $(window).on('beforeunload') = function() {
    // 	console.log('JQ onbeforeunload');
	// };
		
	$(window).on('body', 'resize', function() {
		console.log('resize');
		// $('#videoBox').css('float', 'right');
		// float:right;
	});

	$('body').on('click', '#confirmModalClose', function() {
		$('#confirmModalConfirm').attr('onClick', '');
		// $('#videoBox').css('float', 'right');
		// float:right;
	});
	// var div = $('#videoBox');
	// div.resizable(
	// {
	// 	stop: function(event, ui)
	// 	{                       
	// 		var top = getTop(ui.helper);
	// 		ui.helper.css('position', 'fixed');
	// 		ui.helper.css('top', top+"px");         
	// 	}       
	// });
	// div.draggable(
	// {
	// 	stop: function(event, ui)
	// 	{           
	// 		var top = getTop(ui.helper);
	// 		ui.helper.css('position', 'fixed');
	// 		ui.helper.css('top', top+"px");
	// 	}
	// });
	// function getTop(ele)
	// {
	// 	var eTop = ele.offset().top;
	// 	var wTop = $(window).scrollTop();
	// 	var top = eTop - wTop;

	// 	return top; 
	// }

	$('body').css('background-color', '#<?php echo $backgroundColor; ?>');
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

	// CookieInterface.call(false);

	$('#myModal').on('hidden.bs.modal', function () {
		$('#editorModal').removeAttr('data-target');
        $('#editorModal').removeAttr('data-backdrop');
        $('#editorModal').removeAttr('data-keyboard');
	    ElastiTools.removeVeil();
	});

	$(".modal").on("shown.bs.modal", function () {
	})

	$('body').data('structureName', '<?php echo framework\kernel\utility\BasicUtils::explodeAndGetElement($container->getRouting()->getPageRoute()->getStructure(), '/', 'last'); ?>');
	// $('body').data('structureName', '<?php echo ''; ?>');
	$('body').data('backgroundEngine', '<?php echo $container->getRouting()->getPageRoute()->getBackgroundEngine(); ?>');
	$('body').data('backgroundTheme', '<?php echo $container->getRouting()->getPageRoute()->getBackgroundTheme(); ?>');
	$('body').data('skinName', '<?php echo $skinName; ?>');

    $('#documentBody').css('display', 'inline');

	if ($('body').data('backgroundEngine') != 'none') {
		Background.call($('body').data('backgroundEngine'), $('body').data('backgroundTheme'));
	}

	$('body').on('click', '.ajaxCaller', function (e) {
		e.preventDefault();
		Structure.handlePageSwitchBehavior();
		Structure.call($(this).parent().attr('href'));
	});

	$('body').on('click', '.elastiSiteAjaxCallerLink', function (e) {
		var esHome = '<?php echo rtrim($container->getConfig()->getGlobal('website.ElastiSiteHomepageLink'), '/'); ?>';
		var thisHome = '<?php echo rtrim($container->getUrl()->getHttpDomain(), '/'); ?>';
		if (esHome != thisHome) {
			return;
		}
		e.preventDefault();
		Structure.handlePageSwitchBehavior();
		var link = $(this)[0];
		Structure.call(link.href);
	});

	$('body').on('click', '.ajaxCallerLink', function (e) {
		e.preventDefault();
		Structure.handlePageSwitchBehavior();
		var link = $(this)[0];
		Structure.call(link.href);
	});

	$('body').on('click', '.divLink', function (e) {
		e.preventDefault();
	});

	$('body').on('click', '.pseudoLink', function (e) {
		e.preventDefault();
	});

	$('body').on('click', '.buttonLink', function (e) {
	    e.preventDefault();
	});

	window.onpopstate = history.onpushstate = function(e) {
		console.log(window.location.href);
		Structure.call(window.location.href, false, false);
		// history.go(-1);
		// console.log('onpopstate', e);
		// console.log('history ', history);
		// console.log('history ', history.back());
		// console.log('e.target', e.target.location.href);
		// Structure.call(e.target.location.href);
	}

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

	// ExitIntent.addEvent(document, 'mouseout', function(evt) {
	// 	if (evt.toElement == null && evt.relatedTarget == null ) {
	// 		ExitIntent.startPopup();
	// 	};
	// });

    $(window).scroll(function () {
        stickyMenuListener();
		stickyFooterListener();
    });

	// document.addEventListener("touchmove", function(event){
	// 	if ($(event.srcElement).hasClass('preventTouchScroll')) {
	// 		event.preventDefault();
	// 	}
	// }, {passive: false});
});
</script>
</html>
