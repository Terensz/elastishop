<!DOCTYPE html>
<?php $httpDomain = $container->getUrl()->getHttpDomain(); ?>
<html lang="en-US">
<head>
<title>Letiltva</title>
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
        <div id="sheetContainer" class="sheetWidth centered">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-one">
					<div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget ExceptionWidget-title">
							A felhasználás letiltása sérülékenységi tesztek végzése miatt
                        </div>
                    </div>

                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <div>
								Mivel súlyosan megsértette az ElastiSite biztonsági szabályzatát,
								így az általános felhasználási feltételeinkben foglaltak alapján
								első lépésként nem tesszük lehetővé a webhely további látogatását. Amennyiben úgy véli,
								hogy félreértés történhetett, úgy az ügyfélszolgálatunk szívesen segít Önnek
								azt tisztázni. Kérjük, a <b><?php echo $container->getSession()->get('visitorCode'); ?></b>
								azonosító a birtokában keresse fel ügyfélkapcsolati felületünket:
								<a href="http://www.elastisite.com/ugyfelkapcsolat">Elastisite Ügyfélkapcsolat</a>.
							</div>
                        </div>
					</div>
					
                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <div>
								Amennyiben nem tisztázza magát, úgy a támadásaival kapcsolatos minden adatot megküldünk a webhely üzemeltetésének, hogy jogi úton léphessen fel Ön ellen. Ön a felhasználás megkezdésekor egyetértett a Felhasználási feltételekkel, amelyben tájékoztattuk Önt arról, hogy a webhelyen sérülékenységi tesztek végzése szigorúan tilos anélkül, hogy írásbeli megállapodással rendelkezne ezeknek a teszteknek az elvégzésére a webhely üzemeltetőivel.
							</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
	</div>
</div>
<script>
$('document').ready(function() {
	console.log('docready');
	$('#backgroundWrapper').remove();
});
</script>
</html>
