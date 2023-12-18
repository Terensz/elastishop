<!DOCTYPE html>
<?php $httpDomain = $container->getUrl()->getHttpDomain(); ?>
<html lang="en-US">
<head>
<title><?php echo 'hiba'; ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="<!--keywords-->">
<meta name="description" content="<!--description-->">
<link rel="icon" href="<?php echo $httpDomain; ?>/accessory/favicon" type="image/x-icon">
<style>
</style>
<?php
include('framework/packages/FrameworkPackage/view/scriptLoader/basicScriptLoader.php');
?>
</head>

<style>
body {
	background-color: #e4e4e4;
}

#backgroundWrapper {
	background-color: #e4e4e4;
	background-repeat: no-repeat;
	position: absolute;
	height: 100%;
	width: 100%;
	/* border: 10px solid #bbbbbb; */
}

/* .backgroundImage {
	position: absolute;
	top: 20px;
	left: 20px;
	min-height: 96%;
	height: auto;
	width: 98%;
	background-image: url('<?php echo $container->getUrl()->getHttpDomain(); ?>/background/image/Simple/error');
	background-repeat: repeat;
	background-size: 100% auto;
	box-shadow: 0 0px 8px #000;
} */
</style>

<body>
<noscript>
	This page needs JavaScript activated to work.
	<style>#documentBody { display:none; }</style>
</noscript>
<div id="documentBody">
	<div id="documentBackground">
		<div id="backgroundWrapper">
			<div class="backgroundImage" class="middleOpacity">
			</div>
		</div>
		<script>
		var BackgroundEngine = {
		    set: function(theme) {

		    },
		    remove: function(theme) {

		    },
		    changeTheme: function(theme) {

		    }
		};
		</script>
	</div>
	<div id="structure">
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-one">
                    <div class="widgetWrapper">
                        <div class="widgetContainer" id="widgetContainer-ExceptionWidget">
                            <b>Hiba<b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
</html>
<script>
$('document').ready(function() {
	// console.log('docready');
	$('#backgroundWrapper').remove();
});
</script>