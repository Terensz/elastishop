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

#sheetContainer {
    position: absolute;
    margin-top: 100px;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    overflow: hidden;
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
        <div id="sheetContainer" class="sheetWidth">
            <div class="row sheetLevel">
                <div class="col-sm-12 widgetRail widgetRail-one">
					<div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget ExceptionWidget-title">
							Figyelmezetetés feltörési kísérlet miatt
                        </div>
                    </div>

                    <div class="widgetWrapper">
                        <div class="widgetContainer widgetContainer-ExceptionWidget">
                            <div>Tisztelt Látogató! Tisztáznunk kell az "etikus hacker" kifejezést: ő egy olyan informatikus, aki szerződésben foglalt feltételek szerint azt vállalja a megbízó részére, hogy sérülékenységet keres a szoftverén. Érdemes áttekintenie az iratait, hogy talál-e ilyen szerződést a webhely üzemeltetőivel. Amennyiben nem, úgy most egy tájákoztatást kap arról, hogy tevékenysége nem "csibészség", hanem bűncselekmény, és feljelentést és szankciókat vonhat maga után. Ezen a ponton megúszta ezzel az írásbeli figyelmezetéssel, amennyiben nem folytatja törvénysértő tevékenységét. </br></br>Ennek az incidensnek nyoma fog maradni az adatbázisunkban.</div>
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
