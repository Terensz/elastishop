<!DOCTYPE html>
<?php $httpDomain = $container->getUrl()->getHttpDomain(); ?>
<html lang="en-US">
<head>
<title>This page cannot be displayed</title>
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

#sheetContainer {
    position: absolute;
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    overflow: hidden;
}

.sheetWidth {
	width: 80%;
}

.centered {
	margin: auto;
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

</style>

<body>
<noscript>
	This page needs JavaScript activated to work.
	<style>body { display:none; }</style>
</noscript>
<div id="documentBody">
	<div id="structure">
        <div id="sheetContainer" class="sheetWidth centered">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-one">
					<div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget ExceptionWidget-title">
							This page cannot be displayed (Error code: 1801)
                        </div>
                    </div>

                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <div>
								Please check the URL address again!
							</div>
                        </div>
					</div>

                </div>
            </div>
        </div>
	</div>
</div>
</html>
