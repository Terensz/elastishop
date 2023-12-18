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

.message-base {
	color: #000000;
}

.message-toggled {
	color: #3535b8;
}

.ExceptionWidget-title {
	font-size: 24px;
	font-weight: bold;
}

.widgetContainer-ExceptionWidget {
	overflow-wrap: break-word;
}

#backgroundWrapper {
	background-color: #e4e4e4;
	background-repeat: no-repeat;
	position: absolute;
	height: 100%;
	width: 100%;
	/* border: 10px solid #bbbbbb; */
}

.widgetContainer {
	background-color: #fff;
	color: #000;
}

.widgetWrapper {
	background-color: #ffffff !important;
    border-radius: 6px;
    background-image: url('../image/FFFFFF-0.8.png');
	color: #363636;
	/* border: 1px solid #cbe3e5; */
    box-shadow: 0 4px 6px #6a6a6a;
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
	<style>body { display:none; }</style>
</noscript>
<div id="documentBody">
	<div id="documentBackground">
		<!-- <div id="backgroundWrapper">
			<div class="backgroundImage" class="middleOpacity">
			</div>
		</div> -->
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
                        <div class="widgetContainer widgetContainer-ExceptionWidget ExceptionWidget-title">
                            <?php echo trans('error'); ?>
                        </div>
                    </div>

                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <b><?php echo $exceptionMessage.' ('.$exceptionCode.')'; ?></b><br>
							<b>File: <?php echo $exceptionFile; ?></b><br>
							<b>Sor: <?php echo $exceptionLine; ?></b>
                        </div>
                    </div>

                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <div>Trace:</div>
                        </div>
                    </div>

<?php
foreach ($exceptionTraces as $exceptionTrace) {
?>
                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <div>File: <b><?php echo $exceptionTrace->getFile(); ?></b></div>
                            <div>Line: <b><?php echo $exceptionTrace->getLine(); ?></b></div>
							<div>Function: <b><?php echo $exceptionTrace->getFunction(); ?></b></div>
							<div>Class: <b><?php echo $exceptionTrace->getClass(); ?></b></div>
							<div>Type: <b><?php echo $exceptionTrace->getType(); ?></b></div>
                            <div>Args:</div>
                            <?php
                            foreach ($exceptionTrace->getArgs() as $arg) {
                            ?>
                                <div><b><?php echo $arg->getKey().': '.$arg->getValue(); ?></b></div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
<?php
}
?>
                </div>
            </div>
        </div>
	</div>
</div>
<script>
$('document').ready(function() {
	console.log('docready');
	$('#backgroundWrapper').remove();
	LoadingHandler.stop();
});
</script>
</html>
